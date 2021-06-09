<?php

namespace App\Libraries\Chat\Models;

use App\Libraries\Chat\Models\Extensions\MessageAttachment;
use App\Libraries\Chat\Models\Extensions\MessageProduct;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Chat\Notifications\NotificationService;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\Chat\BaseModel;
use App\Libraries\Chat\Chat;
use App\Libraries\Chat\ConfigurationManager;
use App\Libraries\Chat\Eventing\AllParticipantsDeletedMessage;
use App\Libraries\Chat\Eventing\EventGenerator;
use App\Libraries\Chat\Eventing\MessageWasSent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Message extends BaseModel
{
    use EventGenerator;

    protected $table = ConfigurationManager::MESSAGES_TABLE;
    protected $primaryKey = 'message_id';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'attachment_id',
        'conversation_id',
        'body',
        'participation_id',
        'type',
    );

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = array(
        'flagged' => 'boolean',
    );

    /**
     * Alias for `message_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('message_id');
    }

    /**
     * Type of Message
     *
     * @return string
     * @author xuanhieupd
     */
    public function getType()
    {
        return $this->getAttribute('type');
    }

    /**
     * @return string
     */
    public function getMessageOverview()
    {
        $authorInfo = $this->participation->messageable;
        $authorInfo = $authorInfo ? $authorInfo : new User();

        switch ($this->getType()) {
            case ConfigurationManager::CHAT_MESSAGE_TYPE_ATTACHMENT:
                return strtr('Gửi đính kèm cho bạn', array(':fullName' => $authorInfo->getFullName()));

            case ConfigurationManager::CHAT_MESSAGE_TYPE_BULK:
                return strtr('Gửi tin khuyến mãi cho bạn', array(':fullName' => $authorInfo->getFullName()));

            case ConfigurationManager::CHAT_MESSAGE_TYPE_PRODUCT:
                return strtr('Gửi sản phẩm cho bạn', array(':fullName' => $authorInfo->getFullName()));

            case ConfigurationManager::CHAT_MESSAGE_TYPE_RECORD:
                return strtr('Gửi bản ghi âm cho bạn', array(':fullName' => $authorInfo->getFullName()));

            case ConfigurationManager::CHAT_MESSAGE_TYPE_TEXT:
                return $this->getAttribute('body');
        }

        return 'Đã gửi một tin nhắn chưa được phân loại';
    }

    /**
     * Thông tin đính kèm với type là loại ghi âm
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function messageAttachment()
    {
        return $this->belongsTo(Attachment::class, 'attachment_id', 'attachment_id');
    }

    /**
     * Danh sách file đính kèm
     *
     * @return BelongsToMany
     * @author xuanhieupd
     */
    public function messageAttachments()
    {
        return $this->belongsToMany(Attachment::class, (new MessageAttachment())->getTable(), 'message_id', 'attachment_id');
    }

    /**
     * Danh sách sản phẩm
     *
     * @return BelongsToMany
     * @author xuanhieupd
     */
    public function messageProducts()
    {
        $productTableName = (new Product())->getTable();

        return $this->belongsToMany(Product::class, (new MessageProduct())->getTable(), 'message_id', 'product_id')
            ->select(array(
                $productTableName . '.product_id',
                'store_id',
                'sku',
                'title',
                'whole_price', 'retail_price', 'import_price', 'collaborator_price',
                'attachment_id'
            ));
    }

    public function participation()
    {
        return $this->belongsTo(Participation::class, 'participation_id', 'participation_id');
    }

    public function getSenderAttribute()
    {
        $participantModel = $this->participation->messageable;

        if (method_exists($participantModel, 'getParticipantDetails')) {
            return $participantModel->getParticipantDetails();
        }

        $fields = Chat::senderFieldsWhitelist();

        return $fields ? $this->participation->messageable->only($fields) : $this->participation->messageable;
    }

    public function unreadCount(Model $participant)
    {
        return MessageNotification::where('messageable_id', $participant->getKey())
            ->where('is_seen', 0)
            ->where('messageable_type', $participant->getMorphClass())
            ->count();
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'conversation_id');
    }

    /**
     * Adds a message to a conversation.
     *
     * @param Conversation $conversationInfo
     * @param $messageParams
     * @param Participation $participant
     * @param string $type
     *
     * @return Model
     */
    public function send(Conversation $conversationInfo, $messageParams, Participation $participant, string $type = 'text'): Model
    {
        $attachmentInfo = isset($messageParams['attachment']) ? $messageParams['attachment'] : null;
        $message = data_get($messageParams, 'message', '');

        $messageInfo = new Message(array(
            'conversation_id' => $conversationInfo->getId(),
            'attachment_id' => $attachmentInfo ? $attachmentInfo->getId() : null,
            'participation_id' => $participant->getId(),
            'body' => blank($message) ? '' : $message,
            'type' => $type,
        ));

        $conversationInfo->touch();
        $messageInfo->save();

        $products = data_get($messageParams, 'products');
        $attachments = data_get($messageParams, 'attachments');

        /* Đính kèm sản phẩm */
        $products instanceof Collection && $products->isNotEmpty() ?
            $messageInfo->messageProducts()->sync(CollectionHelper::pluckUnique($products, 'product_id')) :
            null;

        /* Đính kèm file ảnh / video */
        $attachments instanceof Collection && $attachments->isNotEmpty() ?
            $messageInfo->messageAttachments()->sync(CollectionHelper::pluckUnique($attachments, 'attachment_id')) :
            null;

        $this->broadcastAfterSend($conversationInfo, $participant, $messageInfo);
        $this->createNotifications($messageInfo, $conversationInfo);

        (new NotificationService())->send($participant, $messageInfo);

        return $messageInfo;
    }

    /**
     * Broadcast message
     *
     * @param $conversationInfo
     * @param $participant
     * @param $messageInfo
     */
    protected function broadcastAfterSend($conversationInfo, $participant, $messageInfo)
    {
        $conversationInfo->load(array('participants'));

        foreach ($conversationInfo->participants as $participantItem) {
            if ($participantItem->getId() === $participant->getId()) continue;

            Chat::broadcasts() ?
                broadcast(new MessageWasSent($participantItem, $messageInfo))->toOthers() :
                event(new MessageWasSent($participantItem, $messageInfo));
        }
    }

    /**
     * Creates an entry in the message_notification table for each participant
     * This will be used to determine if a message is read or deleted.
     *
     * @param Message $message
     * @param Conversation $conversationInfo
     */
    protected function createNotifications(Message $message, Conversation $conversationInfo)
    {
        MessageNotification::make($message, $conversationInfo);
    }

    /**
     * Deletes a message for the participant.
     *
     * @param Model $participant
     *
     * @return void
     */
    public function trash(Model $participant): void
    {
        MessageNotification::where('messageable_id', $participant->getKey())
            ->where('messageable_type', $participant->getMorphClass())
            ->where('message_id', $this->getKey())
            ->delete();

        if ($this->unDeletedCount() === 0) {
            event(new AllParticipantsDeletedMessage($this));
        }
    }

    public function unDeletedCount()
    {
        return MessageNotification::where('message_id', $this->getKey())
            ->count();
    }

    /**
     * Return user notification for specific message.
     *
     * @param Model $participant
     *
     * @return MessageNotification
     */
    public function getNotification(Model $participant): MessageNotification
    {
        return MessageNotification::where('messageable_id', $participant->getKey())
            ->where('messageable_type', $participant->getMorphClass())
            ->where('message_id', $this->getId())
            ->select([
                '*',
                'updated_at as read_at',
            ])
            ->first();
    }

    /**
     * Marks message as read.
     *
     * @param $participant
     */
    public function markRead($participant): void
    {
        $this->getNotification($participant)->markAsRead();
    }

    public function flagged(Model $participant): bool
    {
        return (bool)MessageNotification::where('messageable_id', $participant->getKey())
            ->where('message_id', $this->getId())
            ->where('messageable_type', $participant->getMorphClass())
            ->where('flagged', 1)
            ->first();
    }

    public function toggleFlag(Model $participant): self
    {
        MessageNotification::where('messageable_id', $participant->getKey())
            ->where('message_id', $this->getId())
            ->where('messageable_type', $participant->getMorphClass())
            ->update(['flagged' => $this->flagged($participant) ? false : true]);

        return $this;
    }
}

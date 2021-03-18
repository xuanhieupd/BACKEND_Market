<?php

namespace App\Libraries\Chat\Models;

use App\Libraries\Chat\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Chat\ConfigurationManager;

class MessageNotification extends BaseModel
{
    use SoftDeletes;

    protected $table = ConfigurationManager::MESSAGE_NOTIFICATIONS_TABLE;
    protected $primaryKey = 'message_notification_id';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'messageable_id',
        'messageable_type',
        'message_id',
        'conversation_id'
    );

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    /**
     * Alias for `message_notification_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('message_notification_id');
    }

    /**
     * Creates a new notification.
     *
     * @param Message $message
     * @param Conversation $conversation
     */
    public static function make(Message $message, Conversation $conversation)
    {
        self::createCustomNotifications($message, $conversation);
    }

    public function unReadNotifications(Model $participant)
    {
        return self::where([
            ['messageable_id', '=', $participant->getKey()],
            ['messageable_type', '=', $participant->getMorphClass()],
            ['is_seen', '=', 0],
        ])->get();
    }

    public static function createCustomNotifications($message, $conversation)
    {
        $notification = [];

        foreach ($conversation->participants as $participation) {
            $is_sender = ($message->participation_id == $participation->getId()) ? 1 : 0;

            $notification[] = [
                'messageable_id' => $participation->messageable_id,
                'messageable_type' => $participation->messageable_type,
                'message_id' => $message->getId(),
                'participation_id' => $participation->getId(),
                'conversation_id' => $conversation->getId(),
                'is_seen' => $is_sender,
                'is_sender' => $is_sender,
                'created_at' => $message->created_at,
            ];
        }

        self::insert($notification);
    }

    public function markAsRead()
    {
        $this->is_seen = 1;
        $this->update(['is_seen' => 1]);
        $this->save();
    }
}

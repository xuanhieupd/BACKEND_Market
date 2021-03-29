<?php

namespace App\Libraries\Chat\Models;

use App\GlobalConstants;
use App\Libraries\Chat\BaseModel;
use App\Libraries\Chat\Facades\ChatFacade as Chat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use App\Libraries\Chat\ConfigurationManager;
use App\Libraries\Chat\Eventing\AllParticipantsClearedConversation;
use App\Libraries\Chat\Eventing\ParticipantsJoined;
use App\Libraries\Chat\Eventing\ParticipantsLeft;
use App\Libraries\Chat\Exceptions\DeletingConversationWithParticipantsException;
use App\Libraries\Chat\Exceptions\DirectMessagingExistsException;
use App\Libraries\Chat\Exceptions\InvalidDirectMessageNumberOfParticipants;

class Conversation extends BaseModel
{
    protected $table = ConfigurationManager::CONVERSATIONS_TABLE;
    protected $primaryKey = 'conversation_id';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'data',
        'direct_message'
    );

    /**
     * @var string[]
     */
    protected $casts = array(
        'data' => 'array',
        'direct_message' => 'boolean',
        'private' => 'boolean',
    );

    /**
     * Alias for `conversation_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('conversation_id');
    }

    public function delete()
    {
        if ($this->participants()->count()) throw new DeletingConversationWithParticipantsException();

        return parent::delete();
    }

    /**
     * Conversation participants.
     *
     * @return HasMany
     */
    public function participants()
    {
        return $this->hasMany(Participation::class, 'conversation_id', 'conversation_id');
    }

    public function getParticipants()
    {
        return $this->participants()->get()->pluck('messageable');
    }

    /**
     * Return the recent message in a Conversation.
     *
     * @return HasOne
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class, 'conversation_id', 'conversation_id')
            ->orderBy($this->getTableMessageWithAppend('.', 'message_id'), 'desc');
    }

    /**
     * Messages in conversation.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'conversation_id'); //->with('sender');
    }

    /**
     * Get messages for a conversation.
     *
     * @param Model $participant
     * @param array $paginationParams
     * @param bool $deleted
     *
     * @return LengthAwarePaginator|HasMany|Builder
     */
    public function getMessages(Model $participant, $paginationParams, $deleted = false)
    {
        return $this->getConversationMessages($participant, $paginationParams, $deleted);
    }

    public function getParticipantConversations($participant, array $options)
    {
        return $this->getConversationsList($participant, $options);
    }

    public function participantFromSender(Model $sender)
    {
        return $this->participants()->where([
            'conversation_id' => $this->getKey(),
            'messageable_id' => $sender->getKey(),
            'messageable_type' => $sender->getMorphClass(),
        ])->first();
    }

    /**
     * Add user to conversation.
     *
     * @param $participants
     *
     * @return Conversation
     */
    public function addParticipants(array $participants): self
    {
        foreach ($participants as $participant) {
            $participant->joinConversation($this);
        }

        event(new ParticipantsJoined($this, $participants));

        return $this;
    }

    /**
     * Remove participant from conversation.
     *
     * @param  $participants
     *
     * @return Conversation
     */
    public function removeParticipant($participants)
    {
        if (is_array($participants)) {
            foreach ($participants as $participant) {
                $participant->leaveConversation($this->getKey());
            }

            event(new ParticipantsLeft($this, $participants));

            return $this;
        }

        $participants->leaveConversation($this->getKey());

        event(new ParticipantsLeft($this, [$participants]));

        return $this;
    }

    /**
     * Starts a new conversation.
     *
     * @param array $payload
     *
     * @return Conversation
     * @throws InvalidDirectMessageNumberOfParticipants
     *
     * @throws DirectMessagingExistsException
     */
    public function start(array $payload): self
    {
        if ($payload['direct_message']) {
            if (count($payload['participants']) > 2) {
                throw new InvalidDirectMessageNumberOfParticipants();
            }

            $this->ensureNoDirectMessagingExist($payload['participants']);
        }

        /** @var Conversation $conversation */
        $conversation = $this->create(['data' => $payload['data'], 'direct_message' => (bool)$payload['direct_message']]);

        if ($payload['participants']) {
            $conversation->addParticipants($payload['participants']);
        }

        return $conversation;
    }

    /**
     * Sets conversation as public or private.
     *
     * @param bool $isPrivate
     *
     * @return Conversation
     */
    public function makePrivate($isPrivate = true)
    {
        $this->private = $isPrivate;
        $this->save();

        return $this;
    }

    /**
     * Sets conversation as direct message.
     *
     * @param bool $isDirect
     *
     * @return Conversation
     * @throws DirectMessagingExistsException
     *
     * @throws InvalidDirectMessageNumberOfParticipants
     */
    public function makeDirect($isDirect = true)
    {
        if ($this->participants()->count() > 2) {
            throw new InvalidDirectMessageNumberOfParticipants();
        }

        $participants = $this->participants()->get()->pluck('messageable');

        $this->ensureNoDirectMessagingExist($participants);

        $this->direct_message = $isDirect;
        $this->save();

        return $this;
    }

    /**
     * @param $participants
     *
     * @throws DirectMessagingExistsException
     */
    private function ensureNoDirectMessagingExist($participants)
    {
        /** @var Conversation $common */
        $common = Chat::conversations()->between($participants[0], $participants[1]);

        if (!is_null($common)) {
            throw new DirectMessagingExistsException();
        }
    }

    /**
     * Gets conversations for a specific participant.
     *
     * @param Model $participant
     * @param bool $isDirectMessage
     *
     * @return Collection
     */
    public function participantConversations(Model $participant, bool $isDirectMessage = false): Collection
    {
        $participationCollection = $participant->participation;

        $conversations = $participationCollection instanceof Collection ?
            $participationCollection->pluck('conversation') :
            collect();

        return $isDirectMessage ? $conversations->where('direct_message', 1) : $conversations;
    }

    /**
     * Get unread notifications.
     *
     * @param Model $participant
     * @return Collection
     * @author xuanhieupd
     */
    public function unReadNotifications(Model $participant): Collection
    {
        return MessageNotification::query()
            ->where('messageable_id', $participant->getKey())
            ->where('messageable_type', $participant->getMorphClass())
            ->where('conversation_id', $this->getId())
            ->where('is_seen', GlobalConstants::STATUS_INACTIVE)
            ->get();
    }

    /**
     * Gets the notifications for the participant.
     *
     * @param  $participant
     * @param bool $readAll
     *
     * @return MessageNotification
     */
    public function getNotifications($participant, $readAll = false)
    {
        return $this->notifications($participant, $readAll);
    }

    /**
     * Clears participant conversation.
     *
     * @param $participant
     *
     * @return void
     */
    public function clear($participant): void
    {
        $this->clearConversation($participant);

        if ($this->unDeletedCount() === 0) {
            event(new AllParticipantsClearedConversation($this));
        }
    }

    /**
     * Marks all the messages in a conversation as read for the participant.
     *
     * @param Model $participant
     *
     * @return void
     */
    public function readAll(Model $participant): void
    {
        $this->getNotifications($participant, true);
    }

    /**
     * Get messages in conversation for the specific participant.
     *
     * @param Model $participant
     * @param $paginationParams
     * @param $deleted
     *
     * @return LengthAwarePaginator|HasMany|Builder
     */
    private function getConversationMessages(Model $participant, $paginationParams, $deleted)
    {
        $messages = $this->messages()
            ->join($this->getTableMessageNotificationWithAppend(), $this->getTableMessageNotificationWithAppend('.', 'message_id'), '=', $this->getTableMessageWithAppend('.', 'message_id'))
            ->where($this->getTableMessageNotificationWithAppend('.', 'messageable_type'), $participant->getMorphClass())
            ->where($this->getTableMessageNotificationWithAppend('.', 'messageable_id'), $participant->getKey());

        $messages = $deleted ?
            $messages->whereNotNull($this->getTableMessageNotificationWithAppend('.', 'deleted_at')) :
            $messages->whereNull($this->getTableMessageNotificationWithAppend('.', 'deleted_at'));

        return $messages
            ->orderBy($this->getTableMessageWithAppend('.', 'message_id'), $paginationParams['sorting'])
            ->paginate(
                $paginationParams['perPage'],
                array(
                    $this->getTableMessageNotificationWithAppend('.', 'updated_at as read_at'),
                    $this->getTableMessageNotificationWithAppend('.', 'deleted_at as deleted_at'),
                    $this->getTableMessageNotificationWithAppend('.', 'messageable_id'),
                    $this->getTableMessageNotificationWithAppend('.', 'message_notification_id as notification_id'),
                    $this->getTableMessageNotificationWithAppend('.', 'is_seen'),
                    $this->getTableMessageNotificationWithAppend('.', 'is_sender'),
                    $this->getTableMessageNotificationWithAppend('.', '*'),
                ),
                $paginationParams['pageName'],
                $paginationParams['page']
            );
    }

    /**
     * @param Model $participant
     * @param $options
     *
     * @return mixed
     */
    private function getConversationsList(Model $participant, $options)
    {
        /** @var Builder $paginator */
        $paginator = $participant->participation()
            ->join($this->getTableConversationWithAppend(' ', 'as c'), $this->getTableParticipationWithAppend('.', 'conversation_id'), '=', 'c.conversation_id')
            ->with([
                'conversation.participants.messageable',
                'conversation.lastMessage' => function ($query) use ($participant) {
                    $query->join($this->getTableMessageNotificationWithAppend(), $this->getTableMessageNotificationWithAppend('.', 'message_id'), '=', $this->getTableMessageWithAppend('.', 'message_id'))
                        ->select(array(
                            $this->getTableMessageNotificationWithAppend('.', '*'),
                            $this->getTableMessageWithAppend('.', '*'),
                        ))
                        ->where($this->getTableMessageNotificationWithAppend('.', 'messageable_id'), $participant->getKey())
                        ->where($this->getTableMessageNotificationWithAppend('.', 'messageable_type'), $participant->getMorphClass())
                        ->whereNull($this->getTableMessageNotificationWithAppend('.', 'deleted_at'));
                },
            ]);

        if (isset($options['filters']['private'])) {
            $paginator = $paginator->where('c.private', (bool)$options['filters']['private']);
        }

        if (isset($options['filters']['direct_message'])) {
            $paginator = $paginator->where('c.direct_message', (bool)$options['filters']['direct_message']);
        }

        return $paginator
            ->orderBy('c.updated_at', 'DESC')
            ->orderBy('c.conversation_id', 'DESC')
            ->distinct('c.conversation_id')
            ->simplePaginate($options['perPage'], [$this->getTableParticipationWithAppend('.', '*'), 'c.*'], $options['pageName'], $options['page']);
    }

    public function unDeletedCount()
    {
        return MessageNotification::where('conversation_id', $this->getKey())->count();
    }

    private function notifications(Model $participant, $readAll)
    {
        $notifications = MessageNotification::where('messageable_id', $participant->getKey())
            ->where($this->getTableMessageNotificationWithAppend('.', 'messageable_type'), $participant->getMorphClass())
            ->where('conversation_id', $this->getId());

        if ($readAll) {
            return $notifications->update(['is_seen' => 1]);
        }

        return $notifications->get();
    }

    private function clearConversation($participant): void
    {
        MessageNotification::where('messageable_id', $participant->getKey())
            ->where($this->getTableMessageNotificationWithAppend('.', 'messageable_type'), $participant->getMorphClass())
            ->where('conversation_id', $this->getKey())
            ->delete();
    }

    public function isDirectMessage()
    {
        return $this->getAttribute('direct_message');
    }
}

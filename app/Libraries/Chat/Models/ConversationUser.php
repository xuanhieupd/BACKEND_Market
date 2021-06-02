<?php

namespace App\Libraries\Chat\Models;

use App\Base\AbstractModelRelation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConversationUser extends AbstractModelRelation
{

    protected $table = 'hnw_chat_conversation_user';
    protected $primaryKey = array(
        'conversation_id',
        'owner_type',
        'owner_id',
    );

    /**
     * @return string
     */
    public function getId()
    {
        return implode('_', array(
            $this->getAttribute('conversation_id'),
            $this->getAttribute('owner_type'),
            $this->getAttribute('owner_id'),
        ));
    }

    /**
     * @return HasMany
     */
    public function conversationUserParticipations()
    {
        return $this->hasMany(Participation::class, 'conversation_id', 'conversation_id');
    }

    /**
     * @return BelongsTo
     */
    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id', 'message_id');
    }

}

<?php

namespace App\Libraries\Chat\Models;

use App\Libraries\Chat\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Libraries\Chat\ConfigurationManager;

class Participation extends BaseModel
{

    protected $table = ConfigurationManager::PARTICIPATION_TABLE;
    protected $primaryKey = 'participation_id';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'conversation_id',
        'messageable_id',
        'messageable_type',
        'messageable_name',
        'settings',
    );

    /**
     * @var string[]
     */
    protected $casts = array(
        'settings' => 'array',
    );

    /**
     * Alias for `participation_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('participation_id');
    }

    /**
     * Conversation.
     *
     * @return BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'conversation_id');
    }

    public function messageable()
    {
        return $this->morphTo();
    }
}

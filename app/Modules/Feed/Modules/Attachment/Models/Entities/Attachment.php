<?php

namespace App\Modules\Feed\Modules\Attachment\Models\Entities;

use App\Base\AbstractModelRelation;

class Attachment extends AbstractModelRelation
{

    protected $table = 'hnw_feed_attachment';
    protected $primaryKey = array('feed_id', 'attachment_id');
    public static $tableAlias = 'hnw_feed_attachment';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'feed_id',
        'attachment_id',
    );

    /**
     * Alias Id
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array($this->getAttribute('feed_id'), $this->getAttribute('attachment_id')));
    }
}

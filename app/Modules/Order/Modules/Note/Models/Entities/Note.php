<?php

/**
 * Note Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Note
 * @copyright (c) 06.10.2020, HNW
 */

namespace App\Modules\Order\Modules\Note\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends AbstractModel
{

    protected $table = 'hnw_order_note';
    protected $primaryKey = 'note_id';
    public static $tableAlias = 'hnw_order_note';

    /**
     * Alias for `note_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('note_id');
    }

    /**
     * Thông tin người ghi chú
     *
     * @return BelongsTo|User
     * @author xuanhieupd
     */
    public function noteUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')->select(array('user_id', 'fullname'));
    }

}

<?php

/**
 * Note Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Note
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Order\Modules\Note\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Order\Modules\Note\Models\Entities\Note;
use App\Modules\Order\Modules\Note\Models\Repositories\Contracts\NoteInterface;

class NoteRepository extends AbstractRepository implements NoteInterface
{

    /**
     * @return Note
     */
    public function model()
    {
        return Note::class;
    }

}

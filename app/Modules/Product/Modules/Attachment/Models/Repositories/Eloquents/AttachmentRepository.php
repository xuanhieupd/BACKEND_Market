<?php

/**
 * Product Attachment Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Attachment
 * @copyright (c) 07.10.2020, HNW
 */

namespace App\Modules\Product\Modules\Attachment\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Product\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Product\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;

class AttachmentRepository extends AbstractRepository implements AttachmentInterface
{

    /**
     * @return Attachment
     */
    public function model()
    {
        return Attachment::class;
    }

}

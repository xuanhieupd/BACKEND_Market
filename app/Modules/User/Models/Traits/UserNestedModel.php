<?php

namespace App\Modules\User\Models\Traits;

use Kalnoy\Nestedset\NodeTrait;

trait UserNestedModel
{
    use NodeTrait;

    /**
     * Get the lft key name.
     *
     * @return  string
     */
    public function getLftName()
    {
        return 'lft';
    }

    /**
     * Get the rgt key name.
     *
     * @return  string
     */
    public function getRgtName()
    {
        return 'rgt';
    }

    /**
     * Get the parent id key name.
     *
     * @return  string
     */
    public function getParentIdName()
    {
        return 'supervisor_id';
    }

}

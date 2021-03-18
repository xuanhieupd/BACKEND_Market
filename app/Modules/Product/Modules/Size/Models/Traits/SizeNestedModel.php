<?php

namespace App\Modules\Product\Modules\Size\Models\Traits;

use Kalnoy\Nestedset\NodeTrait;

trait SizeNestedModel
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
        return 'parent_id';
    }
}

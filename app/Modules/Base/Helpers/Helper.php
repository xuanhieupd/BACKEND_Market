<?php

namespace App\Modules\Base\Helpers;

use Illuminate\Support\Collection;

class Helper
{

    /**
     * Build cây nhị phân
     *
     * @param $datas
     * @param int $parentId
     * @return Collection
     */
    public static function toRecursive($datas, $parentId = 0)
    {
        $recursiveResults = collect();

        foreach ($datas as $index => $itemInfo) {
            $parentName = $itemInfo->getParentIdName();
            if ($itemInfo->getId() == 0 || $itemInfo->getAttribute($parentName) != $parentId) {
                continue;
            }

            $datas->forget($index);
            $children = self::toRecursive($datas, $itemInfo->getId());

            $itemInfo->setAttribute('childrens', $children);
            $recursiveResults->push($itemInfo);
        }

        return $recursiveResults;
    }

}

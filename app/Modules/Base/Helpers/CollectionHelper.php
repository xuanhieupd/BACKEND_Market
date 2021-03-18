<?php


namespace App\Modules\Base\Helpers;


use Illuminate\Support\Collection;

class CollectionHelper
{

    /**
     * @param Collection $datas
     * @param $fieldName
     * @return Collection
     */
    public static function pluckUnique($datas, $fieldName)
    {
        return $datas->pluck($fieldName)->unique()->values();
    }

}

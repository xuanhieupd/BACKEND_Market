<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Category\Models\Entities\Category;
use App\GlobalConstants;
use Illuminate\Support\Facades\DB;

class CategoryFixTreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tableName = (new Category())->getTable();

        $categoryInfo = Category::query()->where('category_id', 0)->first();
        if (!$categoryInfo) {
            $categoryInfo = new Category(array(
                'store_id' => 0,
                'code' => 'ROOT',
                'title' => 'ROOT',
                'description' => '',
                'icon' => '',
                'parent_id' => null,
                'rgt' => 0,
                'lft' => 0,
                'level' => 0,
                'children_ids' => json_encode(array()),
                'init' => GlobalConstants::STATUS_ACTIVE,
                'status' => 0,
            ));

            $categoryInfo->save();

            DB::unprepared("UPDATE $tableName SET `category_id` = '0' WHERE `hnw_category`.`category_id` = " . $categoryInfo->getId());
        }

        Category::fixTree();
    }
}

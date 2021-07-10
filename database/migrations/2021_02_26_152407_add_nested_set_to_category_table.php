<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Category\Models\Entities\Category;
use Illuminate\Support\Facades\DB;

class AddNestedSetToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function up()
    {
        $tableName = (new Category())->getTable();
        DB::unprepared("ALTER TABLE $tableName CHANGE `parent_id` `parent_id` INT(11) NULL;");
        DB::unprepared("ALTER TABLE $tableName AUTO_INCREMENT=0;");

        Schema::table((new Category())->getTable(), function (Blueprint $table) {
            $table->unsignedInteger('lft')->default(0)->after('parent_id');
            $table->unsignedInteger('rgt')->default(0)->after('parent_id');

            $table->index(array('lft', 'rgt', 'parent_id'));
        });

        Category::fixTree();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function down()
    {
        $tableName = (new Category())->getTable();
        DB::unprepared("ALTER TABLE $tableName AUTO_INCREMENT=1;");

        Schema::table((new Category())->getTable(), function (Blueprint $table) {
            $table->dropColumn(array('lft', 'rgt'));
            $table->dropIndex(array('lft', 'rgt', 'parent_id'));
        });
    }
}

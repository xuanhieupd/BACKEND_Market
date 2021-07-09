<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToHnwFeedProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hnw_feed_product', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hnw_feed_product', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}

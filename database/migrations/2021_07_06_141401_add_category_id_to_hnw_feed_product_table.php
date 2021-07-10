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
        Schema::create('hnw_feed', function (Blueprint $table) {
            $table->bigIncrements('feed_id');
            $table->string('author_type');
            $table->unsignedInteger('author_id');
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('hnw_feed_product', function (Blueprint $table) {
            $table->bigInteger('feed_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('category_id');
            $table->index(['feed_id', 'product_id']);
        });

        Schema::create('hnw_feed_comment', function (Blueprint $table) {
            $table->unsignedInteger('feed_id');
            $table->string('author_type');
            $table->unsignedInteger('author_id');
            $table->mediumText('message');
            $table->timestamps();
        });

        Schema::create('hnw_feed_attachment', function (Blueprint $table) {
            $table->unsignedInteger('feed_id');
            $table->unsignedInteger('attachment_id');
            $table->index(['feed_id', 'attachment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hnw_feed');
        Schema::drop('hnw_feed_product');
        Schema::drop('hnw_feed_comment');
        Schema::drop('hnw_feed_attachment');
    }
}

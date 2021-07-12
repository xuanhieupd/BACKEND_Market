<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hnw_slide', function(Blueprint $table){
            $table->increments('slide_id');
            $table->unsignedInteger('user_id');
            $table->string('image');
            $table->string('url');
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('hnw_likeable_like', function (Blueprint $table){
            $table->string('author_type');
            $table->unsignedInteger('author_id');
        });

        Schema::create('hnw_product_user_seen', function(Blueprint $table){
             $table->unsignedInteger('product_id');
             $table->unsignedInteger('user_id');
             $table->timestamps();
             $table->index(['product_id', 'user_id']);
        });

        \Illuminate\Support\Facades\DB::table('hnw_slide')->insert(array(
            array(
                'user_id' => 1,
                'image' => 'https://f17-zpg.zdn.vn/8134759026301950677/e4ae3e95521ba645ff0a.jpg',
                'url' => '#',
                'status' => 1
            ),
            array(
                'user_id' => 1,
                'image' => 'https://media.foody.vn/images/beauty-upload-api-675x355-210126112235.jpg',
                'url' => '#',
                'status' => 1
            ),
            array(
                'user_id' => 1,
                'image' => 'https://media.foody.vn/images/beauty-upload-api-675x355-%284%29-210125100259.jpg',
                'url' => '#',
                'status' => 1
            )
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hnw_slide');
        Schema::drop('hnw_product_user_seen');
    }
}

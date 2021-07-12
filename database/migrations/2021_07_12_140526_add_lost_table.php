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

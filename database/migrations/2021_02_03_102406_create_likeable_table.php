<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Likeable\Models\Entities\LikeCounter;
use App\Modules\Likeable\Models\Entities\Like;

class CreateLikeableTable extends Migration
{
    public function up()
    {
        Schema::create((new Like())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('likeable_type', 255);
            $table->unsignedInteger('likeable_id');

            $table->unsignedInteger('user_id')->index();
            $table->timestamps();
            $table->unique(['likeable_id', 'likeable_type', 'user_id'], 'likeable_likes_unique');
        });

        Schema::create((new LikeCounter())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('likeable_id');
            $table->string('likeable_type', 255);
            $table->unsignedBigInteger('count')->default(0);
            $table->unique(['likeable_id', 'likeable_type'], 'likeable_counts');
        });
    }

    public function down()
    {
        Schema::drop('hnw_likeable_like');
        Schema::drop('hnw_likeable_like_counter');
    }
}

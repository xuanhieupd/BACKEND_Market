<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Likeable\Models\Entities\Like;

class AddStatusColumnToLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table((new Like())->getTable(), function (Blueprint $table) {
            $table->tinyInteger('status')
                ->after('user_id')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table((new Like())->getTable(), function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

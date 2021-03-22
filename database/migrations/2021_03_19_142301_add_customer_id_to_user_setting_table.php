<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Store\Modules\SettingUser\Models\Entities\SettingUser;

class AddCustomerIdToUserSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function up()
    {
        Schema::table((new SettingUser())->getTable(), function (Blueprint $table) {
            $table->unsignedInteger('customer_id')
                ->nullable()
                ->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function down()
    {
        Schema::table((new SettingUser())->getTable(), function (Blueprint $table) {
            $table->dropColumn(array('customer_id'));
        });
    }
}

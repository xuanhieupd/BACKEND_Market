<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Libraries\Chat\Models\Message;

class AddAttachmentIdToMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function up()
    {
        Schema::table((new Message())->getTable(), function (Blueprint $table) {
            $table->unsignedInteger('attachment_id')
                ->after('message_id')
                ->nullable();
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
        Schema::table((new Message())->getTable(), function (Blueprint $table) {
            $table->dropColumn(array('attachment_id'));
        });
    }
}

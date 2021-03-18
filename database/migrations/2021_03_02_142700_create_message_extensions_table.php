<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Libraries\Chat\Models\Extensions\MessageAttachment;
use App\Libraries\Chat\Models\Extensions\MessageRecord;
use App\Libraries\Chat\Models\Extensions\MessageProduct;

class CreateMessageExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @author xuanhieupd
     */
    public function up()
    {
        Schema::create((new MessageAttachment())->getTable(), function (Blueprint $table) {
            $table->unsignedInteger('message_id');
            $table->unsignedInteger('attachment_id');

            $table->primary(array('message_id', 'attachment_id'), 'ex_message_attachment_id');
        });

        Schema::create((new MessageRecord())->getTable(), function (Blueprint $table) {
            $table->unsignedInteger('message_id');
            $table->unsignedInteger('attachment_id');

            $table->primary(array('message_id', 'attachment_id'), 'ex_message_record_id');
        });

        Schema::create((new MessageProduct())->getTable(), function (Blueprint $table) {
            $table->unsignedInteger('message_id');
            $table->unsignedInteger('product_id');

            $table->primary(array('message_id', 'product_id'), 'ex_message_product_id');
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
        Schema::dropIfExists((new MessageAttachment())->getTable());
        Schema::dropIfExists((new MessageRecord())->getTable());
        Schema::dropIfExists((new MessageProduct())->getTable());
    }
}

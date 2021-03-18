<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Libraries\Chat\Models\Conversation;
use App\Libraries\Chat\Models\Message;
use App\Libraries\Chat\Models\Participation;
use App\Libraries\Chat\Models\MessageNotification;

class CreateChatTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getConversationTable(), function (Blueprint $table) {
            $table->bigIncrements('conversation_id');
            $table->boolean('private')->default(true);
            $table->boolean('direct_message')->default(false);
            $table->text('data')->nullable();
            $table->timestamps();
        });

        Schema::create($this->getParticipationTable(), function (Blueprint $table) {
            $table->bigIncrements('participation_id');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('messageable_id')->unsigned();
            $table->string('messageable_type');
            $table->text('settings')->nullable();
            $table->timestamps();

            $table->unique(['conversation_id', 'messageable_id', 'messageable_type'], 'participation_index');

            $table->foreign('conversation_id')
                ->references('conversation_id')
                ->on($this->getConversationTable())
                ->onDelete('cascade');
        });

        Schema::create($this->getMessageTable(), function (Blueprint $table) {
            $table->bigIncrements('message_id');
            $table->text('body');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('participation_id')->unsigned()->nullable();
            $table->string('type')->default('text');
            $table->timestamps();

            $table->foreign('participation_id')
                ->references('participation_id')
                ->on($this->getParticipationTable())
                ->onDelete('set null');

            $table->foreign('conversation_id')
                ->references('conversation_id')
                ->on($this->getConversationTable())
                ->onDelete('cascade');
        });

        Schema::create($this->getMessageNotificationTable(), function (Blueprint $table) {
            $table->bigIncrements('message_notification_id');
            $table->bigInteger('message_id')->unsigned();
            $table->bigInteger('messageable_id')->unsigned();
            $table->string('messageable_type');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('participation_id')->unsigned();
            $table->boolean('is_seen')->default(false);
            $table->boolean('is_sender')->default(false);
            $table->boolean('flagged')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(array('participation_id', 'message_id'), 'participation_message_index');

            $table->foreign('message_id')
                ->references('message_id')
                ->on($this->getMessageTable())
                ->onDelete('cascade');

            $table->foreign('conversation_id')
                ->references('conversation_id')
                ->on($this->getConversationTable())
                ->onDelete('cascade');

            $table->foreign('participation_id')
                ->references('participation_id')
                ->on($this->getParticipationTable())
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getMessageNotificationTable());
        Schema::dropIfExists($this->getMessageTable());
        Schema::dropIfExists($this->getParticipationTable());
        Schema::dropIfExists($this->getConversationTable());
    }

    /**
     * @return string
     */
    protected function getMessageTable()
    {
        return (new Message())->getTable();
    }

    /**
     * @return string
     */
    protected function getParticipationTable()
    {
        return (new Participation())->getTable();
    }

    /**
     * @return string
     */
    protected function getConversationTable()
    {
        return (new Conversation())->getTable();
    }

    protected function getMessageNotificationTable()
    {
        return (new MessageNotification())->getTable();
    }
}

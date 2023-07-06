<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('body');
            $table->unsignedInteger('sender_id')->index('direct_messages_sender_id_foreign')->comment('user id of the sender');
            $table->unsignedInteger('receiver_id')->comment('user id of the receiver');
            $table->unsignedInteger('attachment_id')->nullable()->index('direct_messages_attachment_id_foreign');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direct_messages');
    }
}

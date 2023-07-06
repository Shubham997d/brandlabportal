<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDirectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direct_messages', function (Blueprint $table) {
            $table->foreign(['attachment_id'])->references(['id'])->on('attachments')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['sender_id'])->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('direct_messages', function (Blueprint $table) {
            $table->dropForeign('direct_messages_attachment_id_foreign');
            $table->dropForeign('direct_messages_sender_id_foreign');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('body')->nullable();
            $table->unsignedInteger('user_id')->index('messages_user_id_foreign');
            $table->unsignedInteger('attachment_id')->nullable()->index('messages_attachment_id_foreign')->comment('id of files table');
            $table->string('messageable_type')->comment('office, team or projects');
            $table->unsignedInteger('messageable_id');
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
        Schema::dropIfExists('messages');
    }
}

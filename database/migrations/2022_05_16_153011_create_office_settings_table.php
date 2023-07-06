<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('office_id')->index('office_settings_office_id_foreign');
            $table->boolean('task_enabled')->default(true)->comment('is task tool enabled');
            $table->boolean('discussion_enabled')->default(true)->comment('is discussion tool enabled');
            $table->boolean('message_enabled')->default(true)->comment('is message tool enabled');
            $table->boolean('event_enabled')->default(true)->comment('is event tool enabled');
            $table->boolean('file_enabled')->default(true)->comment('is file tool enabled');
            $table->timestamps();
            $table->boolean('roadmap_enabled')->default(false)->comment('whether roadmap option is enabled or not');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_settings');
    }
}

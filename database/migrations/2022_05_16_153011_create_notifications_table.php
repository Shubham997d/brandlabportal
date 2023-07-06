<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('type');
            $table->boolean('visible_to_admin')->nullable();
            $table->tinyInteger('visible_to_user')->nullable();
            $table->string('action_slug')->nullable();
            $table->string('action_name')->nullable();
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('group_type')->nullable()->comment('project/team/office');
            $table->longText('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->integer('group_id')->nullable();
            $table->string('model_name')->nullable();
            $table->unsignedInteger('model_id')->nullable();

            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}

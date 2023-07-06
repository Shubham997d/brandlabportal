<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestoneTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_task', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('milestone_id')->index('milestone_task_milestone_id_foreign');
            $table->unsignedInteger('task_id')->index('milestone_task_task_id_foreign');
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
        Schema::dropIfExists('milestone_task');
    }
}

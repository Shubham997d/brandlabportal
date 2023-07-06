<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMilestoneTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('milestone_task', function (Blueprint $table) {
            $table->foreign(['milestone_id'])->references(['id'])->on('milestones')->onDelete('CASCADE');
            $table->foreign(['task_id'])->references(['id'])->on('tasks')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('milestone_task', function (Blueprint $table) {
            $table->dropForeign('milestone_task_milestone_id_foreign');
            $table->dropForeign('milestone_task_task_id_foreign');
        });
    }
}

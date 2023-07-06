<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->boolean('completed')->default(false);
            $table->unsignedInteger('assigned_to')->nullable()->index('tasks_assigned_to_foreign');
            $table->unsignedInteger('created_by')->index('tasks_created_by_foreign');
            $table->boolean('archived')->default(false);
            $table->text('notes')->nullable();
            $table->date('due_on')->nullable();
            $table->string('task_duration')->nullable();
            $table->string('related_to')->nullable()->comment('ids of task that are related with this');
            $table->string('taskable_type')->comment('office, team or projects');
            $table->unsignedInteger('taskable_id');
            $table->unsignedInteger('cycle_id')->nullable();
            $table->timestamps();
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable()->index('tasks_parent_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}

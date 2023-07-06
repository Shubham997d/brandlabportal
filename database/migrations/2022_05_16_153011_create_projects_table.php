<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('project_manager_id')->nullable()->comment('Which User Project Belongs To');
            $table->tinyInteger('status')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('cost')->nullable();
            $table->string('currency_code', 100)->nullable();
            $table->string('deadline', 250)->nullable();
            $table->unsignedInteger('office_id')->nullable()->index('projects_office_id_foreign')->comment('id of office, if any, under which this project operates');
            $table->unsignedInteger('team_id')->nullable()->index('projects_team_id_foreign')->comment('id of team, if any, under which this project operates');
            $table->unsignedInteger('owner_id')->index('projects_owner_id_foreign')->comment('user id of the owner of the project');
            $table->integer('deal_id')->nullable();
            $table->timestamps();
            $table->boolean('public')->default(false)->comment('is open to non-user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}

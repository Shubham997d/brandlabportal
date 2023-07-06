<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreign(['office_id'])->references(['id'])->on('offices')->onDelete('CASCADE');
            $table->foreign(['owner_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['team_id'])->references(['id'])->on('teams')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_office_id_foreign');
            $table->dropForeign('projects_owner_id_foreign');
            $table->dropForeign('projects_team_id_foreign');
        });
    }
}

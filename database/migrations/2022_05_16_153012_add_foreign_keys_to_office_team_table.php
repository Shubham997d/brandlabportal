<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOfficeTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_team', function (Blueprint $table) {
            $table->foreign(['office_id'])->references(['id'])->on('offices')->onDelete('CASCADE');
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
        Schema::table('office_team', function (Blueprint $table) {
            $table->dropForeign('office_team_office_id_foreign');
            $table->dropForeign('office_team_team_id_foreign');
        });
    }
}

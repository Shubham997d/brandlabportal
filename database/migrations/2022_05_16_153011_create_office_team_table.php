<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_team', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('office_id')->index('office_team_office_id_foreign');
            $table->unsignedInteger('team_id')->index('office_team_team_id_foreign');
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
        Schema::dropIfExists('office_team');
    }
}

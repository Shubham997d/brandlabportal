<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('office_id')->nullable()->index('teams_office_id_foreign')->comment('id of office, if any, under which this team operates');
            $table->unsignedInteger('owner_id')->index('teams_owner_id_foreign')->comment('user id of the owner of the project');
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
        Schema::dropIfExists('teams');
    }
}

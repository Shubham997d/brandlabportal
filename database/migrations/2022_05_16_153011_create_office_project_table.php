<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_project', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('office_id')->index('office_project_office_id_foreign');
            $table->unsignedInteger('project_id')->index('office_project_project_id_foreign');
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
        Schema::dropIfExists('office_project');
    }
}

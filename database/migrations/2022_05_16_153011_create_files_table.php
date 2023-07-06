<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('path');
            $table->string('hash');
            $table->string('mime_type');
            $table->string('fileable_type')->comment('office, team or projects');
            $table->unsignedInteger('fileable_id');
            $table->unsignedInteger('cycle_id')->nullable();
            $table->timestamps();
            $table->unsignedInteger('owner_id')->default('1')->index('files_owner_id_foreign')->comment('user id of the owner of the file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}

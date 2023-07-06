<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleHasPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->index('role_has_permission_role_id_foreign');
            $table->unsignedInteger('permission_id')->index('role_has_permission_permission_id_foreign');
            $table->unsignedInteger('group_id')->nullable();
            $table->string('group_type')->nullable();
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
        Schema::dropIfExists('role_has_permission');
    }
}

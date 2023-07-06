<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->index('permission_settings_role_id_foreign');
            $table->unsignedInteger('permission_id')->index('permission_settings_permission_id_foreign');
            $table->boolean('group_related')->comment('this permission settings is for resource under a group (project/team/office)');
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
        Schema::dropIfExists('permission_settings');
    }
}

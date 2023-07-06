<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowedPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowed_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permission_id')->index('allowed_permissions_permission_id_foreign');
            $table->unsignedInteger('user_id')->index('allowed_permissions_user_id_foreign');
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
        Schema::dropIfExists('allowed_permissions');
    }
}

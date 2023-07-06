<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('bio')->nullable();
            $table->string('designation')->nullable();
            $table->string('avatar')->nullable();
            $table->string('colour')->nullable();
            $table->float('salary', 10, 0)->nullable();
            $table->float('yearly_salary', 10, 0);
            $table->string('email')->unique();
            $table->unsignedInteger('role_id')->default('5')->index('users_role_id_foreign');
            $table->boolean('active')->comment('is user active or deactivated');
            $table->string('password');
            $table->string('timezone')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->enum('week_start', ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])->nullable();
            $table->string('lang')->default('en');
            $table->string('location')->nullable();
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

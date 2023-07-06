<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOfficeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_user', function (Blueprint $table) {
            $table->foreign(['office_id'])->references(['id'])->on('offices')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_user', function (Blueprint $table) {
            $table->dropForeign('office_user_office_id_foreign');
            $table->dropForeign('office_user_user_id_foreign');
        });
    }
}

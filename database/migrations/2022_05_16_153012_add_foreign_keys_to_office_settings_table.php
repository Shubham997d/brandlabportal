<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOfficeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_settings', function (Blueprint $table) {
            $table->foreign(['office_id'])->references(['id'])->on('offices')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_settings', function (Blueprint $table) {
            $table->dropForeign('office_settings_office_id_foreign');
        });
    }
}

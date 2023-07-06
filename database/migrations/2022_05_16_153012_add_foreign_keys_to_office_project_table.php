<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOfficeProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_project', function (Blueprint $table) {
            $table->foreign(['office_id'])->references(['id'])->on('offices')->onDelete('CASCADE');
            $table->foreign(['project_id'])->references(['id'])->on('projects')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_project', function (Blueprint $table) {
            $table->dropForeign('office_project_office_id_foreign');
            $table->dropForeign('office_project_project_id_foreign');
        });
    }
}

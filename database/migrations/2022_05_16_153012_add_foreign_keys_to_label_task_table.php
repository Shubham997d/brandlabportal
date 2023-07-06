<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLabelTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('label_task', function (Blueprint $table) {
            $table->foreign(['label_id'])->references(['id'])->on('labels')->onDelete('CASCADE');
            $table->foreign(['task_id'])->references(['id'])->on('tasks')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('label_task', function (Blueprint $table) {
            $table->dropForeign('label_task_label_id_foreign');
            $table->dropForeign('label_task_task_id_foreign');
        });
    }
}

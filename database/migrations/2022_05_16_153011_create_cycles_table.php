<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cycles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cyclable_type');
            $table->unsignedInteger('cyclable_id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->date('start_date')->nullable()->comment('date of start of the cycle');
            $table->date('end_date')->nullable()->comment('date of end of the cycle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cycles');
    }
}

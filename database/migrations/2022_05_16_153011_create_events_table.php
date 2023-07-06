<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->text('time')->comment('format - {"day":"Mon","start":"05:58","end":"15:58"}');
            $table->string('place')->nullable()->comment('where the event will take place');
            $table->unsignedInteger('created_by')->index('events_created_by_foreign')->comment('id of the user who created the event');
            $table->string('eventable_type')->comment('office, team or projects');
            $table->unsignedInteger('eventable_id');
            $table->unsignedInteger('cycle_id')->nullable();
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
        Schema::dropIfExists('events');
    }
}

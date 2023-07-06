<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_activities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('deal_id')->nullable();
            $table->string('activity_data', 200)->nullable();
            $table->string('timeline_time_in', 200)->nullable();
            $table->string('timeline_time_out', 200)->nullable();
            $table->string('activity_subject', 250)->nullable();
            $table->string('activity_type', 250)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('creator_id')->nullable();
            $table->string('schedule_status', 200);
            $table->string('deal_name', 200)->nullable();
            $table->longText('notes');
            $table->boolean('status')->default(false);
            $table->string('status_datetime', 250)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_activities');
    }
}

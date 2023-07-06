<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('pipeline_stage', 100);
            $table->integer('owner_id')->nullable();
            $table->integer('creator_id')->nullable();
            $table->string('status', 100)->nullable()->default('1');
            $table->string('deal_won_datetime', 200)->nullable();
            $table->dateTime('deal_lost_datetime')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
}

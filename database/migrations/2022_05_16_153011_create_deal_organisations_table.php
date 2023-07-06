<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealOrganisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_organisations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('deal_id', 100)->nullable();
            $table->string('organisation')->nullable();
            $table->string('title')->nullable();
            $table->string('deal_color')->nullable();
            $table->string('deal_color_update_datetime')->nullable();
            $table->bigInteger('turnover')->nullable();
            $table->string('currency_symbol', 100)->nullable()->comment('Set default currecny according to client');
            $table->string('currency_code', 100)->nullable()->comment('Set default currecny according to client');
            $table->string('expected_close_date', 200)->nullable();
            $table->text('address')->nullable();
            $table->string('website', 100)->nullable();
            $table->text('phone')->nullable();
            $table->dateTime('created_at')->useCurrent();
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
        Schema::dropIfExists('deal_organisations');
    }
}

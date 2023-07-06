<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesPipelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_pipelines', function (Blueprint $table) {
            $table->integer('id', true);
            $table->longText('company_info')->nullable();
            $table->string('timeline', 200)->nullable();
            $table->string('budget', 200)->nullable();
            $table->longText('project_info')->nullable();
            $table->string('selling_type', 100)->nullable();
            $table->string('contract_stage', 100)->nullable();
            $table->text('onboarding_call_details')->nullable();
            $table->string('turnover', 200)->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('sales_pipelines');
    }
}

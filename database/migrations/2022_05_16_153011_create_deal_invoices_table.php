<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_invoices', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('deal_id')->nullable();
            $table->json('content')->nullable();
            $table->integer('invoice_type')->nullable();
            $table->json('invoice_content')->nullable();
            $table->string('invoice_date', 200)->nullable();
            $table->string('invoice_number', 200)->nullable();
            $table->string('file_name', 200)->nullable();
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
        Schema::dropIfExists('deal_invoices');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('completed')->default(false);
            $table->unsignedInteger('created_by');
            $table->boolean('archived')->default(false);
            $table->string('asset_type')->nullable();
            $table->string('asset_cost')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('status_id')->nullable();
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
        Schema::dropIfExists('assets');
    }
}

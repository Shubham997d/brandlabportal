<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('content')->comment('html string');
            $table->text('raw_content')->comment('content in delta format');
            $table->unsignedInteger('posted_by')->index('discussions_posted_by_foreign');
            $table->boolean('archived')->default(false);
            $table->boolean('draft')->default(true);
            $table->string('discussionable_type')->comment('office, team or projects');
            $table->unsignedInteger('discussionable_id');
            $table->unsignedInteger('category_id')->index('discussions_category_id_foreign');
            $table->unsignedInteger('cycle_id')->nullable();
            $table->timestamps();
            $table->boolean('implemented')->default(false)->comment('whether this feature is implemented');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discussions');
    }
}

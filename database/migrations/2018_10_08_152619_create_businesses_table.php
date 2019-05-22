<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name');
            $table->integer('internal_score')->default(0);
            $table->integer('score')->default(80);
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('business_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('category_id');
            $table->integer('relevance');
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->index('relevance');
        });

        Schema::create('business_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->string('day_of_week');
            $table->integer('open_period_mins');
            $table->integer('close_period_mins');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_hours');
        Schema::dropIfExists('business_category');
        Schema::dropIfExists('businesses');
    }
}

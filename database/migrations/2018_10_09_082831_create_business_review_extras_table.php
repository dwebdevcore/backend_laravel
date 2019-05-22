<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessReviewExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_review_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedInteger('business_review_id');
            $table->text('data')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('business_review_id')->references('id')->on('business_reviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_review_extras');
    }
}

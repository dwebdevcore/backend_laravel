<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessReviewKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_review_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedInteger('business_review_id');
            $table->string('keyword');
            $table->float('relevance');
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
        Schema::dropIfExists('business_review_keywords');
    }
}

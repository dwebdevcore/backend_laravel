<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('code');
            $table->text('comment')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->index(['business_id', 'code']);

            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_reviews');
    }
}

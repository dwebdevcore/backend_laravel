<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->dateTime('expire_date')->nullable();
            $table->text('text')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('business_post_images', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedInteger('business_post_id');
            $table->string('path');
            $table->integer('sid')->nullable();
            $table->boolean('cover')->default(false);
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('business_post_id')->references('id')->on('business_posts');
            $table->index('cover');
        });

        Schema::create('business_post_image_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedInteger('business_post_image_id');
            $table->string('label');
            $table->integer('confidence');
            $table->string('src');
            $table->string('cat_0');
            $table->string('cat_1');
            $table->string('cat_2');
            $table->string('cat_3');
            $table->string('cat_4');
            $table->string('cat_5');
            $table->string('cat_6');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('business_post_image_id')->references('id')->on('business_post_images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_post_images');
        Schema::dropIfExists('business_posts');
    }
}

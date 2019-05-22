<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapPresetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_presets', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->string('name');
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('map_preset_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('map_preset_id');
            $table->integer('open_period_mins');
            $table->integer('close_period_mins');
            $table->boolean('wd_0')->default(false);
            $table->boolean('wd_1')->default(false);
            $table->boolean('wd_2')->default(false);
            $table->boolean('wd_3')->default(false);
            $table->boolean('wd_4')->default(false);
            $table->boolean('wd_5')->default(false);
            $table->boolean('wd_6')->default(false);
            $table->enum('repeat', ['weekly', 'monthly'])->default('weekly');
            $table->text('dates')->nullable();
            $table->json('days')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('map_preset_id')->references('id')->on('map_presets');
        });

        Schema::create('map_preset_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('map_preset_id');
            $table->unsignedInteger('category_id');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('map_preset_id')->references('id')->on('map_presets');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('map_preset_categories');
        Schema::dropIfExists('map_preset_hours');
        Schema::dropIfExists('map_presets');
    }
}

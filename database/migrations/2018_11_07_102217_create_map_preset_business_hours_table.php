<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapPresetBusinessHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_preset_business_hours', function (Blueprint $table) {
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('map_preset_business_hours');
    }
}

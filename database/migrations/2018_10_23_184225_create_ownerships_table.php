<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ownerships', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('request_id')->nullable();
            $table->timestamps();

            $table->primary(['user_id', 'business_id']);

            $table->index('business_id');
            $table->index('request_id');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('business_id')
                ->references('id')->on('businesses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('request_id')
                ->references('id')->on('ownership_requests')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ownerships');
    }
}

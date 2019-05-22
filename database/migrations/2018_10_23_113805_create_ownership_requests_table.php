<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnershipRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ownership_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('business_id');
            $table->enum('method', ['email', 'phone', 'support']);
            $table->string('address');
            $table->string('token')->nullable();
            $table->json('user_info');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('business_id');
            $table->index('method');
            $table->index('confirmed_at');
            $table->index('created_at');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('business_id')
                ->references('id')->on('businesses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ownership_requests');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('issued_by');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->boolean('renewable')->default(false);
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_orders');
    }
};

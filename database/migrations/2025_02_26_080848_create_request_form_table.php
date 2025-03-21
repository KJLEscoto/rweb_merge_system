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
        Schema::create('request_forms', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->date('date');
            $table->string('status');
            $table->text('description');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add this line for soft deletes

            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_forms');
    }
};

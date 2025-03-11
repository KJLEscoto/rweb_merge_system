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
        Schema::create('web_job_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_signed_draft_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('supervisor_signed_draft_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('client_signed_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('web_job_orders');
    }
};

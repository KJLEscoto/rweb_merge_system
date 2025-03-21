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
        Schema::create('web_project_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('web_job_order_id')->nullable()->constrained('web_job_orders')->cascadeOnDelete();
            $table->foreignId('feedback_id')->nullable()->constrained('web_feedback')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('web_projects')->cascadeOnDelete();
            $table->string('status')->nullable();
            $table->string('sub_status')->nullable();
            $table->string('type')->nullable();
            $table->string('draft')->nullable();
            $table->date('date_started')->nullable();
            $table->date('date_targeted')->nullable();
            $table->date('date_completed')->nullable();
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
        Schema::dropIfExists('web_project_channels');
    }
};

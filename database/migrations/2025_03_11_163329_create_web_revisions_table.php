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
        Schema::create('web_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_job_order_id')->nullable()->constrained('web_job_orders')->cascadeOnDelete();
            $table->foreignId('declined_by_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('summary')->nullable();
            $table->string('last_draft')->nullable();
            $table->string('latest_draft')->nullable();
            $table->date('date_submitted')->nullable();
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
        Schema::dropIfExists('web_revisions');
    }
};

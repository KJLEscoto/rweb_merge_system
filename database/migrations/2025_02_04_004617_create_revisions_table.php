<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_draft_id');
            $table->unsignedBigInteger('declined_by');
            $table->text('summary');
            $table->text('last_draft');
            $table->text('submitted_draft')->nullable();
            $table->timestamp('date_submitted')->nullable();
            $table->timestamp('revision_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('status');

            // Foreign Key Constraint
            $table->foreign('job_draft_id')->references('id')->on('job_drafts')->onDelete('cascade');
            $table->foreign('declined_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisions');
    }
};

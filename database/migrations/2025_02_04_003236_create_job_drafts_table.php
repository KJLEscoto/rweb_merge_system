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
        Schema::create('job_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_order_id');
            $table->enum('type', ['content_writer', 'graphic_designer']);
            $table->text('draft')->nullable();
            $table->date('date_started')->default(DB::raw('CURRENT_DATE'));
            $table->date('date_target');
            $table->longText('signature_admin')->nullable();
            $table->unsignedBigInteger('admin_signed')->nullable();
            $table->longText('signature_supervisor')->nullable();
            $table->unsignedBigInteger('supervisor_signed')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('content_writer_id');
            $table->unsignedBigInteger('graphic_designer_id');
            $table->unsignedBigInteger('client_id');
            $table->string('feedback')->nullable();
            $table->date('date_completed')->nullable();
            $table->unsignedBigInteger('reference_draft_id')->nullable();

            // Foreign Key Constraint
            $table->foreign('job_order_id')->references('id')->on('job_orders')->onDelete('cascade');
            $table->foreign('content_writer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('graphic_designer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_signed')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_signed')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reference_draft_id')->references('id')->on('job_drafts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_drafts');
    }
};

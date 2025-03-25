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
        Schema::create('soas', function (Blueprint $table) {
            $table->id();
            $table->text('bill_from');
            $table->string('telephone');
            $table->string('company'); //
            $table->string('client_name');
            $table->text('address');
            $table->date('billing_date')->nullable();
            $table->date('due_date')->nullable();

            $table->unsignedBigInteger('job_draft_id'); //
            $table->unsignedBigInteger('prepared_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('image_path'); //
            $table->string('status'); //
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('job_draft_id')->references('id')->on('job_drafts')->onDelete('cascade');
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soas');
    }
};

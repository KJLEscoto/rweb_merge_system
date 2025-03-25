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
        Schema::create('soa_particulars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soa_id');

            $table->integer('quantity');
            $table->text('particulars');
            $table->double('charges');
            $table->string('credits');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('soa_id')->references('id')->on('soas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soa_particulars');
    }
};

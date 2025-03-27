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
        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->nullable();
            $table->date('expiration_domain')->nullable();
            $table->string('station')->nullable();
            $table->string('hosting')->nullable();
            $table->date('expiration_hosting')->nullable();
            $table->string('customer')->nullable();
            $table->decimal('amount', 10, 2)->nullable(); // Assuming 'AMOUNT' is a decimal
            $table->string('status')->nullable();
            $table->string('soa_for_hosting')->nullable();
            $table->string('payment_for_hosting')->nullable();
            $table->string('soa_for_domain')->nullable();
            $table->string('payment_for_domain')->nullable();
            $table->string('column_1')->nullable(); // Assuming 'Column 1' is a general string column
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
        Schema::dropIfExists('renewals');
    }
};

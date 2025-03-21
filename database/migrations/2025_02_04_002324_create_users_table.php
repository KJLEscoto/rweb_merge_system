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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable(); // Foreign key reference
            $table->string('name')->nullable();
            $table->string('phone', 20)->unique();
            $table->text('address');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->longText('signature')->nullable();
            $table->rememberToken();
            $table->timestamps();

            //sasas migration columns
            //addons for the users
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename');

            //composite key not to be duplicate
            $table->unique(['firstname', 'lastname', 'middlename']);
            
            $table->string('gender')->default('male');

            //school name
            $table->string('school')->nullable();

            //student number
            $table->string('student_no')->unique();

            //auto generated when the user create the account
            $table->string('qr_code')->default('');

            //starting date
            //this is different from the timestamp(created_at) date
            $table->date('starting_date')->nullable();

            //emergency contact
            $table->string('emergency_contact_number');
            $table->string('emergency_contact_fullname');
            $table->string('emergency_contact_address');

            //hidden column
            $table->string('role')->default('user');

            //expiry for the account
            $table->date('expiry_date')->nullable();

            //status for the account
            $table->string('status')->default('active');

            // Foreign key constraint
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

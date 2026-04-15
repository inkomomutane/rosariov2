<?php

use App\Enum\Sex;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nip')->nullable()->unique()->comment('Unique police number identification');
            $table->rememberToken();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('sex')->nullable();
            $table->string('language')->nullable();
            $table->jsonb('other_languages')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed','other'])->nullable();
            $table->string('id_number')->nullable();
            $table->date('id_emitted_at')->nullable();
            $table->date('id_expires_at')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_emitted_at')->nullable();
            $table->date('passport_expires_at')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('driver_licence')->nullable();
            $table->date('driver_licence_emitted_at')->nullable();
            $table->date('driver_licence_expires_at')->nullable();
            $table->string('dir')->nullable();
            $table->date('dir_emitted_at')->nullable();
            $table->date('dir_expires_at')->nullable();


            # contact info
            $table->jsonb('contacts')->nullable()->comment('[{ contact: string, type: personal|emergency, contact_person : string }]');
            $table->jsonb('primary_contact')->nullable();
            $table->jsonb('emails')->nullable()->comment('[{ email: string, type: personal|work }]');
            $table->jsonb('primary_email')->nullable();
            $table->jsonb('addresses')->nullable()->comment('[{country,city,province,address,zip/postal code}]');

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('password_reset_tokens', static function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('email')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
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

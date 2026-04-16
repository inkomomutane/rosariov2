<?php

use App\Enum\MaritalStatus;
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
        Schema::create('people', static  function (Blueprint $table) {

            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->string('gender')->default(Sex::OTHER)->nullable();
            $table->string('marital_status')->nullable()->default(MaritalStatus::SINGLE);
            $table->string('nationality')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->text('address_three')->nullable();
            $table->text('address_four')->nullable();

            $table->string('phone_one')->nullable();
            $table->string('phone_two')->nullable();
            $table->string('phone_three')->nullable();
            $table->string('phone_four')->nullable();


            $table->date('death_date')->nullable();
            $table->text('cause_of_death')->nullable();


            $table->string('created_by_name')->nullable();

            $table->string('created_by_id')->nullable();
            $table->string('user_id')->nullable();

            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};

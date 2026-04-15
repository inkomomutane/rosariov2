<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('education', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('school')->nullable();
            $table->string('degree')->nullable();
            $table->string('field_of_study');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('grade')->nullable();
            $table->text('activities')->nullable();
            $table->text('description')->nullable();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};

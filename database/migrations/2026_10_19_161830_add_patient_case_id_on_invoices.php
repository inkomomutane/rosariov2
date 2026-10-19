<?php

use App\Models\PatientCase;
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
        Schema::table('invoices', static function (Blueprint $table) {
            $table->foreignUlid(PatientCase::class)->nullable()->after('id')->constrained('patient_cases')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', static function (Blueprint $table) {
            $table->dropForeign([PatientCase::class]);
            $table->dropColumn(PatientCase::class);
        });
    }
};

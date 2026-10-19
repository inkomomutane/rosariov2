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

            $table->string('patient_case_id')->nullable()->after('id');
            $table->foreign('patient_case_id')->references('id')->on('patient_cases')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', static function (Blueprint $table) {
            $table->dropForeign(['patient_case_id']);
            $table->dropColumn('patient_case_id');
        });
    }
};

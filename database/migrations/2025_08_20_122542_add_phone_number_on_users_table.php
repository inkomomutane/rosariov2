<?php

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


        DB::unprepared("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM pg_class c
                    JOIN pg_namespace n ON n.oid = c.relnamespace
                    WHERE c.relkind = 'S' AND c.relname = 'document_no_seq'
                ) THEN
                    CREATE SEQUENCE document_no_seq
                        INCREMENT BY 1
                        MINVALUE 1
                        START WITH 1
                        CACHE 10 OWNED BY NONE;
                END IF;
            END
            $$;
        ");

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->bigInteger('document_no')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::unprepared("DROP SEQUENCE IF EXISTS document_no_seq;");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('document_no');
        });
    }
};

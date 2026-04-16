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

        Schema::createFunctionOrReplace(
            name: 'invoice_row_calc',
            parameters: [],
            return: 'trigger',language:  'plpgsql',
            body: '
                BEGIN
                    NEW.subtotal = COALESCE(NEW.price, 0) * COALESCE(NEW.quantity, 0);
                    NEW.total = COALESCE(NEW.subtotal,0) - COALESCE(NEW.discount,0) + COALESCE(NEW.tax,0);
                    NEW.remaining = COALESCE(NEW.total,0) - COALESCE(NEW.paid,0);

                    IF NEW.due_date IS NOT NULL AND NEW.due_date < CURRENT_DATE THEN
                        NEW.due = COALESCE(NEW.remaining,0);
                    ELSE
                        NEW.due = 0;
                    END IF;

                    RETURN NEW;
                END;
            '
        );

        Schema::create('invoice_rows', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('invoice_code');
            $table->text('description');
            $table->date('due_date')->nullable();

            $table->decimal('quantity', 18, 6)->default(1);
            $table->decimal('price', 18, 6)->default(0);
            $table->decimal('discount', 18, 6)->default(0);
            $table->decimal('tax', 18, 6)->default(0);
            $table->decimal('subtotal', 18, 6)->default(0);
            $table->decimal('total', 18, 6)->default(0);

            $table->decimal('paid', 18, 6)->default(0);
            $table->decimal('remaining', 18, 6)->default(0);
            $table->decimal('due', 18, 6)->default(0);

            $table->dropTriggerIfExists('invoice_row_calc_trigger');
            $table->trigger('invoice_row_calc_trigger','invoice_row_calc()','BEFORE INSERT OR UPDATE')->forEachRow()->replace();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_rows');
        Schema::dropFunctionIfExists('invoice_row_calc');
    }
};

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('payer');
            $table->datetime('due_on');
            $table->decimal('vat', 5, 2)->default(0);
            $table->boolean('is_vat_inclusive')->default(true);
            $table->enum('transaction_type', ['full_payment', 'part_payment', 'no_payment'])->default('no_payment');
            $table->string('status')->nullable();
            $table->text('transaction_ref');

            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

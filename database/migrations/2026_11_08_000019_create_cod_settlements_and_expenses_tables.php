<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cod_settlements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers_profiles')->nullOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('BDT');
            $table->string('status', 32)->default('pending');
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('category', 64);
            $table->string('description');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('BDT');
            $table->timestamp('incurred_at')->nullable();
            $table->nullableMorphs('reference');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('cod_settlements');
    }
};

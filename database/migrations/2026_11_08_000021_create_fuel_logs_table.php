<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->decimal('liters', 12, 3);
            $table->decimal('cost', 14, 2)->default(0);
            $table->unsignedInteger('odometer')->nullable();
            $table->timestamp('filled_at')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'filled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};

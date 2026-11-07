<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_locations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('drivers_profiles')->cascadeOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->decimal('speed', 8, 2)->nullable();
            $table->decimal('heading', 8, 2)->nullable();
            $table->timestamp('recorded_at')->index();
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->timestamps();

            $table->index(['business_id', 'driver_id', 'recorded_at'], 'driver_locations_live_tracking_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};

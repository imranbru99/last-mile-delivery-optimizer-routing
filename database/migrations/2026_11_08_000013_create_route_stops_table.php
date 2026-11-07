<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_stops', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
            $table->foreignId('shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('sequence')->default(0);
            $table->string('type')->default('delivery');
            $table->string('status')->default('pending')->index();
            $table->timestamp('eta_at')->nullable();
            $table->timestamp('ata_at')->nullable();
            $table->unsignedSmallInteger('service_time_min')->default(5);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->json('address_snapshot')->nullable();
            $table->timestamps();

            $table->index(['route_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_stops');
    }
};

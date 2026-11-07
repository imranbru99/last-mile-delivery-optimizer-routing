<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('plate_number')->index();
            $table->string('type')->default('van');
            $table->decimal('capacity_weight_kg', 12, 3)->default(0);
            $table->decimal('capacity_volume_m3', 12, 3)->default(0);
            $table->unsignedSmallInteger('capacity_stops')->default(50);
            $table->string('status')->default('available')->index();
            $table->foreignId('hub_id')->nullable()->constrained('hubs')->nullOnDelete();
            $table->json('documents')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['business_id', 'plate_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

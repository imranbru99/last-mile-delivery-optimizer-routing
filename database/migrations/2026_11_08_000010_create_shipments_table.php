<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('tracking_code')->unique();
            $table->foreignId('merchant_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_level')->default('standard');
            $table->unsignedTinyInteger('priority')->default(5);
            $table->string('status')->default('pending')->index();
            $table->foreignId('pickup_address_id')->constrained('addresses')->cascadeOnDelete();
            $table->foreignId('delivery_address_id')->constrained('addresses')->cascadeOnDelete();
            $table->foreignId('hub_id')->nullable()->constrained('hubs')->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->decimal('weight_kg', 12, 3)->default(0);
            $table->decimal('volume_m3', 12, 3)->default(0);
            $table->unsignedSmallInteger('pieces')->default(1);
            $table->timestamp('time_window_start')->nullable();
            $table->timestamp('time_window_end')->nullable();
            $table->decimal('cod_amount', 14, 2)->default(0);
            $table->string('currency', 3)->default('BDT');
            $table->json('special_handling')->nullable();
            $table->string('barcode')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'status']);
            $table->index(['business_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};

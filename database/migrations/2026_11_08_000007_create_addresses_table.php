<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_code', 2)->default('BD');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->text('formatted')->nullable();
            $table->string('geocode_status')->nullable();
            $table->json('geocode_raw')->nullable();
            $table->string('what3words')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'postal_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};

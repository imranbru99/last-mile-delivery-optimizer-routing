<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('category')->index();
            $table->json('dependencies')->nullable();
            $table->string('version')->default('1.0.0');
            $table->boolean('default_enabled')->default(false);
            $table->boolean('always_on')->default(false);
            $table->string('pricing_tier')->default('starter')->index();
            $table->json('settings_schema')->nullable();
            $table->text('what_you_lose')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

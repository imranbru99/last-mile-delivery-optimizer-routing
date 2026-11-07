<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('business_type')->default('custom')->index();
            $table->string('logo_path')->nullable();
            $table->string('primary_color')->default('#0F766E');
            $table->string('secondary_color')->default('#134E4A');
            $table->string('custom_domain')->nullable()->unique();
            $table->string('locale', 10)->default('en');
            $table->string('currency', 3)->default('BDT');
            $table->string('timezone')->default('Asia/Dhaka');
            $table->string('distance_unit', 10)->default('km');
            $table->string('country_code', 2)->default('BD');
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('plan')->default('trial')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};

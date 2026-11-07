<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('business_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('locale', 10)->default('en')->after('password');
            $table->string('timezone')->default('Asia/Dhaka')->after('locale');
            $table->string('avatar_path')->nullable()->after('timezone');
            $table->boolean('is_active')->default(true)->after('avatar_path');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->text('two_factor_secret')->nullable()->after('last_login_ip');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            $table->foreignId('impersonated_business_id')->nullable()->after('two_factor_confirmed_at')->constrained('businesses')->nullOnDelete();
            $table->string('role_hint')->nullable()->after('impersonated_business_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('impersonated_business_id');
            $table->dropConstrainedForeignId('business_id');
            $table->dropColumn([
                'phone',
                'locale',
                'timezone',
                'avatar_path',
                'is_active',
                'last_login_at',
                'last_login_ip',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'role_hint',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('premium_until')->nullable()->after('remember_token');
            $table->timestamp('banned_until')->nullable()->after('premium_until');
            $table->softDeletes()->after('banned_until');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['premium_until', 'banned_until']);
        });
    }
};
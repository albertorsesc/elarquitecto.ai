<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->json('affiliate_data')->nullable()->after('documentation_url');
            $table->json('research_data')->nullable()->after('affiliate_data');
        });
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn(['affiliate_data', 'research_data']);
        });
    }
};

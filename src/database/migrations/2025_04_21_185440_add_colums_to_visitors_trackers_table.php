<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('locale', 25)->nullable();
            $table->string('ip_address', 128)->nullable();
            $table->string('browser_type', 128)->nullable();
            $table->string('browser_family', 128)->nullable();
            $table->string('browser_version', 128)->nullable();
            $table->string('browser_engine', 128)->nullable();
            $table->string('platform_family', 128)->nullable();
            $table->string('platform_version', 128)->nullable();
            $table->string('device_family', 128)->nullable();
            $table->string('device_model', 128)->nullable();
        });

        Schema::table('trackers', function (Blueprint $table) {
            $table->string('session_name', 128)->nullable()->after('visitor_id');
            $table->index(['session_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn([
                'locale',
                'ip_address',
                'browser_type',
                'browser_family',
                'browser_version',
                'browser_engine',
                'platform_family',
                'platform_version',
                'device_family',
                'device_model'
            ]);
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('session_name');
        });
    }
};

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
            $table->string('city', 256)->nullable();
            $table->string('continent', 128)->nullable();
            $table->string('country', 256)->nullable();
            $table->string('currency', 128)->nullable();
            $table->string('iso_code', 128)->nullable();
            $table->string('postal_code', 128)->nullable();
            $table->string('timezone', 128)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'continent',
                'country',
                'currency',
                'iso_code',
                'postal_code',
                'timezone'
            ]);
        });
    }
};

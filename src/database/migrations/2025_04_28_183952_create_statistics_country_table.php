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
        Schema::create('statistics_country', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('user_id');
            $table->string('country', 256)->nullable()->default('');
            $table->integer('unique_visits')->default(0);
            $table->integer('visits')->default(0);
            $table->integer('events')->default(0);
            $table->date('date_visits');
            $table->timestamps();

            $table->index(['resource_id', 'user_id', 'date_visits']);
            $table->index(['resource_id', 'date_visits']);
            $table->index(['resource_id']);
            $table->index(['country']);
            $table->index(['date_visits']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics_country');
    }
};

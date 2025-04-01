<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trackers', function (Blueprint $table) {
            $table->id();
            $table->string('hostname');
            $table->string('href')->nullable();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('visitor_id')->nullable();
            $table->dateTime('time_event');
            $table->string('target')->nullable();
            $table->string('target_id')->nullable();
            $table->integer('mouse_x')->default(0);
            $table->integer('mouse_y')->default(0);
            $table->integer('browser_w')->default(0);
            $table->integer('browser_h')->default(0);
            $table->json('event_data')->default(new Expression('(JSON_OBJECT())'));
            $table->timestamps();

            $table->index(['event_id']);
            $table->index(['resource_id']);
            $table->index(['visitor_id']);
            $table->index(['target']);
            $table->index(['target_id']);

            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('visitor_id')
                ->references('id')
                ->on('visitors')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackers');
    }
};

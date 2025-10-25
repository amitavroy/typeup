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
        Schema::create('click_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_id')->constrained('searches', 'search_id')->onDelete('cascade');
            $table->string('content_id');
            $table->integer('position');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['search_id', 'created_at']);
            $table->index(['content_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_events');
    }
};

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
        Schema::create('search_request_suggestion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('suggestion_id')->constrained()->cascadeOnDelete();
            $table->unique(['search_request_id', 'suggestion_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_request_suggestion');
    }
};

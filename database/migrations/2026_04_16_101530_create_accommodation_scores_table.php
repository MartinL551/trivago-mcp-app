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
        Schema::create('accommodation_scores', function (Blueprint $table) {
            $table->id();
            $table->integer('accommodation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('search_request_id')->constrained()->cascadeOnDelete();
            $table->string('trivago_id')->unique();
            $table->integer('romance');
            $table->integer('adventure');
            $table->integer('budget');
            $table->integer('luxury');
            $table->integer('business');
            $table->integer('family');
            $table->string('why');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_scores');
    }
};

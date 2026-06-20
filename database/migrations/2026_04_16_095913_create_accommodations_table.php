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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('trivago_id')->unique();
            $table->foreignId('search_request_id')->constrained()->cascadeOnDelete();
            $table->integer('accommodationscore_id')->unique()->nullable();
            $table->string('name');
            $table->string('currency');
            $table->float('price_per_stay');
            $table->float('price_per_night');
            $table->integer('hotel_rating');
            $table->string('city');
            $table->float('review_rating');
            $table->integer('review_count');
            $table->string('amenites');
            $table->string('trivago_url');
            $table->string('trivago_image_url');
            $table->string('distance_string')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->date('arrival')->nullable();
            $table->date('departure')->nullable();
            $table->string('advertiser')->nullable();
            $table->timestamps();
            $table->unique(['search_request_id', 'trivago_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};

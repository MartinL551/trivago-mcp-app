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
            $table->integer('accommodationscore_id')->unique();
            $table->string('name');
            $table->string('postcode');
            $table->string('address');
            $table->string('currency');
            $table->string('price_per_stay');
            $table->string('price_per_day');
            $table->integer('rating');
            $table->string('city');
            $table->string('review_rating');
            $table->string('review_count');
            $table->string('amenites');
            $table->string('trivago_url');
            $table->string('trivago_image_url');
            $table->string('distance_string');
            $table->float('distance_to_center');
            $table->string('distance_units');
            $table->string('desc');
            $table->timestamps();
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

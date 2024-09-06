<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tour_cities', function (Blueprint $table) {
            $table->foreignId('tour_id');
            $table->foreignId('city_id');
            $table->integer('stop_order')->nullable();
            $table->timestamps();

            // Composite primary key
            $table->primary(['tour_id', 'city_id']);

            // Foreign key constraints
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_cities');
    }
};

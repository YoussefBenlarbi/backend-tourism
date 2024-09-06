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
    Schema::create('enquiry_data', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tour_id')->constrained();
        $table->string('name');
        $table->string('email');
        $table->date('arrival_date')->nullable();
        $table->date('departure_date')->nullable();
        $table->integer('number_of_travelers')->nullable();
        $table->text('message')->nullable();
        $table->enum('status', ['pending', 'processed', 'cancelled'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_data');
    }
};

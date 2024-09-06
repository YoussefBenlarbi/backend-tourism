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
    Schema::create('tours', function (Blueprint $table) {
        $table->id();
        $table->foreignId('destination_id')->constrained();
        $table->foreignId('category_id')->constrained();
        $table->string('name');
        $table->string('title')->nullable();
        $table->text('description')->nullable();
        $table->string('duration')->nullable();
        $table->decimal('price', 10, 2)->nullable();
        $table->string('image_url')->nullable();
        $table->enum('status', ['active', 'inactive', 'sold out'])->default('active');
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->integer('max_travelers')->nullable();
        $table->enum('difficulty_level', ['easy', 'moderate', 'challenging'])->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};

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
        Schema::create('tour_destinations', function (Blueprint $table) {
            $table->foreignId('tour_id')
                  ->constrained()
                  ->onDelete('cascade'); // Foreign key referencing `tours` with cascade on delete
                  
            $table->foreignId('destination_id')
                  ->constrained()
                  ->onDelete('cascade'); // Foreign key referencing `destinations` with cascade on delete
                  
            $table->integer('stop_order');
            $table->timestamps();
            
            // Adding composite primary key
            $table->primary(['tour_id', 'destination_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tour_destinations');
    }
    
};

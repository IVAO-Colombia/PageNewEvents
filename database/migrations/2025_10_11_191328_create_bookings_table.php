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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Name_event')->constrained('events', 'id');
            $table->foreignId('route_id')->constrained('routes', 'id');
            $table->string('Callsign')->nullable();
            $table->string('Departure')->nullable();
            $table->string('Arrival')->nullable();
            $table->string('VID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

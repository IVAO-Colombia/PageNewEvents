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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('hourOrigin')->nullable();
            $table->string('hourDestination')->nullable();
            $table->string('flight_number')->unique();
            $table->string('airline')->nullable();
            $table->string('origin');
            $table->string('name_airport_origin')->nullable();
            $table->string('longitude_origin')->nullable();
            $table->string('latitude_origin')->nullable();
            $table->string('iato_code_origin')->nullable();
            $table->string('gate_origin')->nullable();
            $table->string('destination');
            $table->string('name_airport_destination')->nullable();
            $table->string('longitude_destination')->nullable();
            $table->string('latitude_destination')->nullable();
            $table->string('iato_code_destination')->nullable();
            $table->string('gate_destination')->nullable();
            $table->string('aircraft_type')->nullable();
            $table->enum('type', ['Arrival', 'Departure', 'none'])->default('none');
            $table->boolean('is_commercial')->default(false);
            $table->boolean('is_international')->default(false);
            $table->boolean('is_cargo')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};

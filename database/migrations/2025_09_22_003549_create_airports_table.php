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
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('ident');
            $table->string('name_airport');
            $table->string('city');
            $table->string('country');
            $table->string('iata_code')->nullable();
            $table->string('icao_code')->nullable();
            $table->float('latitude_deg')->nullable();
            $table->float('longitude_deg')->nullable();
            $table->integer('elevation_ft')->nullable();
            $table->string('timezone')->nullable();
            $table->string('dst')->nullable();
            $table->string('tz_database_time_zone')->nullable();
            $table->string('type')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};

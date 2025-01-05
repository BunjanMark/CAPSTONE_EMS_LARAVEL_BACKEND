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
        Schema::create('event_services_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('user_id');
            $table->string('service_provider_name');
            $table->date('event_booked_date'); // Date of the event
            $table->time('event_booked_time_start'); // Start time of the event
            $table->time('event_booked_time_end'); // End time of the event
            $table->timestamps();
    
            // Foreign keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_services_providers');
    }
};

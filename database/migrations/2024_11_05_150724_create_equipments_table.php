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
        
        Schema::create('equipments', function (Blueprint $table) {
            $table->id(); // Laravel default primary key (id)
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade'); // Foreign key to events table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('account_role_id')->constrained('account_roles')->onDelete('cascade');
            $table->string('item')->nullable();
            $table->integer('number_of_items')->nullable();
            $table->integer('number_of_sort_items')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_user', function (Blueprint $table) {
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
       
        Schema::dropIfExists('equipment_user');
        Schema::dropIfExists('equipments');
    }
};

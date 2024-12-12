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
        Schema::create('account_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  
            $table->unsignedBigInteger('role_id');  
            $table->string('service_provider_name');
            $table->string('description')->nullable();
            $table->timestamps();

            // Add index and foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_roles');
    }
};

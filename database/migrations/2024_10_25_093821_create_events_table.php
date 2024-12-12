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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->integer('pax');
            $table->string('status');
            $table->decimal('totalPrice', 10, 2)->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->text('description');
            $table->longText('coverPhoto')->nullable();
            $table->json('packages');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  
            $table->timestamps();
            $table->string('payment_status')->nullable();

        });
    }


    public function down()
    {
        Schema::dropIfExists('events');
    }
};

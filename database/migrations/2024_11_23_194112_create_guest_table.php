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
    Schema::create('guest', function (Blueprint $table) {
        $table->id(); // This will act as GuestID
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->string('GuestName')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->string('role')->nullable();
        $table->string('status')->default('Absent');
        $table->timestamps();
        $table->boolean('notifiable')->default(true);
    });
}

    public function down()
    {
        Schema::dropIfExists('guest');
    }

   
};

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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('inventory_status')->default('available');
            $table->integer('quantity')->default(0);
            $table->integer('quantity_sorted')->default(0);
            $table->string('equipment_status')->default('good');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};

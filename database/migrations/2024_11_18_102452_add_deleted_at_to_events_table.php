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
        Schema::table('events', function (Blueprint $table) {
            $table->softDeletes(); // Adds the `deleted_at` column
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes the `deleted_at` column
        });
    }
    // public function up(): void
    // {
    //     Schema::table('events', function (Blueprint $table) {
    //         //
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::table('events', function (Blueprint $table) {
    //         //
    //     });
    // }
};

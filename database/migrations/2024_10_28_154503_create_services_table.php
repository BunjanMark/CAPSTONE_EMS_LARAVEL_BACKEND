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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Deletes services if the user is deleted
            $table->string('serviceName'); // Service name
            $table->string('serviceCategory'); // Service category
            $table->string('location')->nullable()->defaultValue("N/a");
            $table->string('serviceFeatures');//package inclusions/features/ contains
            $table->longText('servicePhotoURL')->nullable();
            $table->boolean('verified')->default(false);
            $table->decimal('basePrice', 10, 2); // Base price with precision
            $table->integer('pax'); // Number of persons (PAX)
            $table->text('requirements')->nullable(); // Requirements, nullable
            $table->boolean('availability_status')->default(true); // Availability status, default true
            $table->string("submitted_by")->nullable(); // Submitted by, nullable
            $table->timestamp("submitted_at")->nullable(); // Submitted at, nullable
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('services');
        // Schema::table('services', function (Blueprint $table) {
        //     $table->dropColumn(['submitted_by', 'submitted_at']);
        // });
    }
 
};

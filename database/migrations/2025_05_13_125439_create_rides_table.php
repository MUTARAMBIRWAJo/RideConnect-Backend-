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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('passengers')->onDelete('cascade');
            $table->foreignId('rider_id')->nullable()->constrained('riders')->onDelete('set null');
            $table->decimal('origin_latitude', 10, 8);
            $table->decimal('origin_longitude', 11, 8);
            $table->decimal('destination_latitude', 10, 8);
            $table->decimal('destination_longitude', 11, 8);
            $table->timestamp('pickup_time')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('fare', 8, 2)->nullable();
            $table->string('payment_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};

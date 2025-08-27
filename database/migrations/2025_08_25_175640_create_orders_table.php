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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('delivery_address');
            $table->geometry('delivery_point', subtype: 'point');
            $table->enum('status', ['pending', 'awaiting_driver', 'assigned', 'rejected', 'delivered', 'cancelled'])
                    ->default('pending');
            $table->foreignId('assigned_delivery_man_id')->nullable()
                    ->constrained('delivery_men')->nullOnDelete();
            $table->timestamps();
            $table->spatialIndex('delivery_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

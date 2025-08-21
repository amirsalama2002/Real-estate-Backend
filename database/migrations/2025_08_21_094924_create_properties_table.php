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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->enum('type', ['apartment', 'villa', 'office', 'land']);
            $table->string('city');
            $table->string('address');
            $table->enum('status', ['available', 'rented', 'sold'])->default('available');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // صاحب العقار
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};

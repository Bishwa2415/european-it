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
        // Each course is sold as Online and/or Offline, with its own price and schedule.
        // A student buys a plan, not the course itself (orders table will point here later).
        Schema::create('course_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('type');                            // online | offline
            $table->string('duration')->nullable();            // e.g. "4 Months"
            $table->unsignedSmallInteger('total_classes')->nullable();
            $table->unsignedTinyInteger('classes_per_week')->nullable();
            $table->decimal('regular_price', 10, 2);           // money: never use float
            $table->decimal('sale_price', 10, 2)->nullable();  // discounted price, if any
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['course_id', 'type']);             // one online + one offline plan per course
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_plans');
    }
};

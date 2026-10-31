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
        // "Why choose European IT" cards (Bangla) on the home page
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');                    // simple HTML: <p>, <ul><li>
            $table->string('icon')->nullable();      // image path
            $table->string('bg_color', 20)->default('#EAF6F0');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};

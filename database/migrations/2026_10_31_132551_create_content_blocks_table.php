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
        // Reusable text + image blocks found by a key, e.g. 'home.specialty'
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('eyebrow')->nullable();   // small label above the title
            $table->string('title');
            $table->text('body')->nullable();        // simple HTML
            $table->string('image')->nullable();
            $table->json('buttons')->nullable();     // [{"label":"Our Courses","url":"/european-it-courses"}]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};

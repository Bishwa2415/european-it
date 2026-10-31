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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();              // URL: /course/{slug}
            $table->string('custom_url')->nullable();      // when the course lives elsewhere (e.g. kidsict.com)
            $table->string('thumbnail')->nullable();       // path inside public/, e.g. images/courses/seo.webp
            $table->string('badge')->nullable();           // advance | popular | new
            $table->text('overview')->nullable();
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('student_count')->default(0);
            $table->boolean('is_popular')->default(false);     // show in home "Popular Courses"
            $table->boolean('is_fourth_ir')->default(false);   // show in home "4th Industrial Courses"
            $table->boolean('is_active')->default(true);       // hide a course without deleting it
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();          // SEO
            $table->string('meta_description')->nullable();    // SEO
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

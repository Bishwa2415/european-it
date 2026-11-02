<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Landing pages: the sales pages under "Popular Courses" in the menu
 * (e.g. /online-wordpress-web-design-development-course-bangladesh).
 * type = online | offline → the two designs of the live site.
 * content = every section's texts, pictures and lists (shape: App\Support\LandingContent::SECTIONS).
 *
 * leads = people who filled the "free webinar / seminar" form on a landing page.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();                       // URL: /{slug}
            $table->string('type', 20)->default('online');          // online | offline
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->json('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 100);
            $table->string('phone', 30);
            $table->string('source', 50)->default('webinar');      // which form sent it
            $table->boolean('is_contacted')->default(false);       // the team ticks it after calling
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('landing_pages');
    }
};

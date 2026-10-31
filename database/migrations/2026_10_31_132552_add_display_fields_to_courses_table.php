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
        Schema::table('courses', function (Blueprint $table) {
            // Short name for small lists, e.g. footer: "Web Development"
            $table->string('short_title')->nullable()->after('title');
            // Show in footer "Popular Courses"
            $table->boolean('in_footer')->default(false)->after('is_fourth_ir');
            // Order on home page sections (sort_order stays for the mega menu)
            $table->unsignedSmallInteger('home_order')->default(0)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['short_title', 'in_footer', 'home_order']);
        });
    }
};

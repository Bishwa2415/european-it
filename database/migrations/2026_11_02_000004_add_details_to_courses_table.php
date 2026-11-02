<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Course detail page content (/course/{slug}) in one JSON column:
 * tagline, intro, banner, curriculum, careers, reasons, mentors, projects, tools, videos, glance.
 * Shape: see App\Models\Course::DETAIL_LISTS. Edited in Dashboard → Courses → Edit → "Detail page".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('details')->nullable()->after('overview');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('details');
        });
    }
};

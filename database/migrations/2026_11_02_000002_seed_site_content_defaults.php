<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $siteSettings = config('site');
        unset($siteSettings['footer']);

        $defaults = [
            ['type' => 'setting', 'title' => 'Site-wide settings', 'slug' => 'site', 'data' => $siteSettings],
            ['type' => 'menu', 'title' => 'Header menu', 'slug' => 'header', 'data' => config('menu')],
            ['type' => 'menu', 'title' => 'Footer menu', 'slug' => 'footer', 'data' => config('site.footer')],
        ];

        foreach ($defaults as $item) {
            DB::table('site_contents')->updateOrInsert(
                ['slug' => $item['slug']],
                [
                    'type' => $item['type'],
                    'title' => $item['title'],
                    'body' => null,
                    'data' => json_encode($item['data'], JSON_THROW_ON_ERROR),
                    'is_active' => true,
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('site_contents')->whereIn('slug', ['site', 'header', 'footer'])->delete();
    }
};

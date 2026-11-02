<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SiteContentController extends Controller
{
    public function page(string $slug): View
    {
        $page = SiteContent::type('page')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('pages.editable', ['content' => $page, 'commonSections' => $this->commonSections($page)]);
    }

    public function post(string $slug): View
    {
        $post = SiteContent::type('post')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('pages.editable', ['content' => $post, 'commonSections' => $this->commonSections($post)]);
    }

    private function commonSections(SiteContent $content): Collection
    {
        $ids = $content->data['sections'] ?? [];

        return SiteContent::type('section')->where('is_active', true)->whereIn('id', $ids)->get()->keyBy('id');
    }
}

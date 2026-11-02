<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentController extends Controller
{
    public const TYPES = [
        'page' => 'Pages', 'post' => 'Posts', 'section' => 'Page sections',
        'popup' => 'Popups / modals', 'menu' => 'Menus', 'setting' => 'Site settings',
    ];

    public function index(string $type): View
    {
        abort_unless(isset(self::TYPES[$type]), 404);

        return view('admin.content.index', [
            'type' => $type,
            'label' => self::TYPES[$type],
            'items' => SiteContent::type($type)->orderBy('sort_order')->orderBy('title')->paginate(20),
        ]);
    }

    public function create(string $type): View
    {
        abort_unless(isset(self::TYPES[$type]), 404);

        return view('admin.content.form', [
            'type' => $type,
            'label' => self::TYPES[$type],
            'item' => new SiteContent(['is_active' => true]),
            'commonSections' => SiteContent::type('section')->where('is_active', true)->orderBy('sort_order')->orderBy('title')->get(),
            'selectedSectionIds' => [],
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        abort_unless(isset(self::TYPES[$type]), 404);
        $item = SiteContent::create($this->validated($request, $type));
        $this->clearFrontendCache($type);

        return redirect()->route('admin.content.edit', [$type, $item])->with('success', self::TYPES[$type].' created.');
    }

    public function edit(string $type, SiteContent $item): View
    {
        $this->ensureType($item, $type);

        return view('admin.content.form', [
            'type' => $type,
            'label' => self::TYPES[$type],
            'item' => $item,
            'commonSections' => SiteContent::type('section')->where('is_active', true)->orderBy('sort_order')->orderBy('title')->get(),
            'selectedSectionIds' => $item->data['sections'] ?? [],
        ]);
    }

    public function update(Request $request, string $type, SiteContent $item): RedirectResponse
    {
        $this->ensureType($item, $type);
        $item->update($this->validated($request, $type));
        $this->clearFrontendCache($type);

        return back()->with('success', self::TYPES[$type].' updated.');
    }

    public function destroy(string $type, SiteContent $item): RedirectResponse
    {
        $this->ensureType($item, $type);
        $item->delete();
        $this->clearFrontendCache($type);

        return redirect()->route('admin.content.index', $type)->with('success', self::TYPES[$type].' deleted.');
    }

    private function validated(Request $request, string $type): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('site_contents', 'slug')->ignore($request->route('item'))],
            'body' => ['nullable', 'string'],
            'data_json' => ['nullable', 'json'],
            'attached_sections' => ['nullable', 'array'],
            'attached_sections.*' => ['integer', Rule::exists('site_contents', 'id')->where('type', 'section')],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $slug = trim((string) ($validated['slug'] ?? ''));
        if (in_array($type, ['page', 'post'], true) && $slug === '') {
            $slug = Str::slug($validated['title']);
        }

        $data = json_decode($validated['data_json'] ?? '{}', true);
        if (! is_array($data)) {
            $data = [];
        }
        if (in_array($type, ['page', 'post'], true)) {
            $data['sections'] = array_values(array_unique(array_map('intval', $validated['attached_sections'] ?? [])));
        }

        return [
            'type' => $type,
            'title' => $validated['title'],
            'slug' => $slug !== '' ? $slug : null,
            'body' => $validated['body'] ?? null,
            'data' => Arr::where($data, fn ($value) => is_scalar($value) || is_null($value) || is_array($value)),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }

    private function ensureType(SiteContent $item, string $type): void
    {
        abort_unless(isset(self::TYPES[$type]) && $item->type === $type, 404);
    }

    private function clearFrontendCache(string $type): void
    {
        if ($type === 'setting') {
            Cache::forget('site-content.settings');
        }
        if ($type === 'menu') {
            Cache::forget('site-content.menu.header');
            Cache::forget('site-content.menu.footer');
        }
        if ($type === 'popup') {
            Cache::forget('site-content.popup');
        }
    }
}

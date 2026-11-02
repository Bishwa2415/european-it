<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\Feature;
use App\Models\Partner;
use App\Models\SuccessVideo;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomeContentController extends Controller
{
    private const COLLECTIONS = [
        'features' => [
            'label' => 'Why choose us cards', 'model' => Feature::class,
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
                ['name' => 'body', 'label' => 'Description (HTML supported)', 'type' => 'textarea', 'required' => true],
                ['name' => 'icon', 'label' => 'Image path', 'type' => 'text', 'hint' => 'Optional image path inside public/, for example images/home/why/support.webp.'],
                ['name' => 'bg_color', 'label' => 'Card background', 'type' => 'color', 'required' => true],
                ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number'],
                ['name' => 'is_active', 'label' => 'Visible on homepage', 'type' => 'toggle'],
            ],
            'rules' => ['title' => ['required', 'string', 'max:255'], 'body' => ['required', 'string'], 'icon' => ['nullable', 'string', 'max:255'], 'bg_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'], 'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'], 'is_active' => ['nullable', 'boolean']],
        ],
        'content-blocks' => [
            'label' => 'Specialty and trusted sections', 'model' => ContentBlock::class,
            'fields' => [
                ['name' => 'key', 'label' => 'Section key', 'type' => 'text', 'required' => true, 'hint' => 'Use home.specialty or home.trusted to edit the homepage sections.'],
                ['name' => 'eyebrow', 'label' => 'Small heading', 'type' => 'text'],
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
                ['name' => 'body', 'label' => 'Description (HTML supported)', 'type' => 'textarea'],
                ['name' => 'image', 'label' => 'Image path', 'type' => 'text'],
                ['name' => 'buttons_json', 'label' => 'Buttons (JSON)', 'type' => 'json', 'hint' => 'Array of buttons with label and url.'],
            ],
            'rules' => ['key' => ['required', 'string', 'max:255'], 'eyebrow' => ['nullable', 'string', 'max:255'], 'title' => ['required', 'string', 'max:255'], 'body' => ['nullable', 'string'], 'image' => ['nullable', 'string', 'max:255'], 'buttons_json' => ['nullable', 'json']],
        ],
        'success-videos' => [
            'label' => 'Success story videos', 'model' => SuccessVideo::class,
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['name' => 'youtube_url', 'label' => 'YouTube URL', 'type' => 'url', 'required' => true],
                ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number'],
                ['name' => 'is_active', 'label' => 'Visible on homepage', 'type' => 'toggle'],
            ],
            'rules' => ['title' => ['nullable', 'string', 'max:255'], 'youtube_url' => ['required', 'url', 'max:255'], 'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'], 'is_active' => ['nullable', 'boolean']],
        ],
        'testimonials' => [
            'label' => 'Testimonials', 'model' => Testimonial::class,
            'fields' => [
                ['name' => 'name', 'label' => 'Student name', 'type' => 'text', 'required' => true],
                ['name' => 'course_name', 'label' => 'Course', 'type' => 'text'],
                ['name' => 'batch', 'label' => 'Batch', 'type' => 'text'],
                ['name' => 'message', 'label' => 'Feedback', 'type' => 'textarea', 'required' => true],
                ['name' => 'photo', 'label' => 'Photo path', 'type' => 'text'],
                ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number'],
                ['name' => 'is_active', 'label' => 'Visible on homepage', 'type' => 'toggle'],
            ],
            'rules' => ['name' => ['required', 'string', 'max:255'], 'course_name' => ['nullable', 'string', 'max:255'], 'batch' => ['nullable', 'string', 'max:255'], 'message' => ['required', 'string'], 'photo' => ['nullable', 'string', 'max:255'], 'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'], 'is_active' => ['nullable', 'boolean']],
        ],
        'team-members' => [
            'label' => 'Support team', 'model' => TeamMember::class,
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                ['name' => 'designation', 'label' => 'Role', 'type' => 'text', 'required' => true],
                ['name' => 'bio', 'label' => 'Biography', 'type' => 'textarea'],
                ['name' => 'photo', 'label' => 'Photo path', 'type' => 'text'],
                ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number'],
                ['name' => 'show_on_home', 'label' => 'Show on homepage', 'type' => 'toggle'],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle'],
            ],
            'rules' => ['name' => ['required', 'string', 'max:255'], 'slug' => ['required', 'string', 'max:255'], 'designation' => ['required', 'string', 'max:255'], 'bio' => ['nullable', 'string'], 'photo' => ['nullable', 'string', 'max:255'], 'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'], 'show_on_home' => ['nullable', 'boolean'], 'is_active' => ['nullable', 'boolean']],
        ],
        'partners' => [
            'label' => 'Partners', 'model' => Partner::class,
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                ['name' => 'logo', 'label' => 'Logo path', 'type' => 'text', 'required' => true],
                ['name' => 'website', 'label' => 'Website', 'type' => 'url'],
                ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number'],
                ['name' => 'is_active', 'label' => 'Visible on homepage', 'type' => 'toggle'],
            ],
            'rules' => ['name' => ['required', 'string', 'max:255'], 'logo' => ['required', 'string', 'max:255'], 'website' => ['nullable', 'url', 'max:255'], 'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'], 'is_active' => ['nullable', 'boolean']],
        ],
    ];

    public function index(string $collection): View
    {
        $settings = $this->settings($collection);
        $model = $settings['model'];
        $query = $model::query();
        if ($collection !== 'content-blocks') {
            $query->orderBy('sort_order');
        }
        $items = $query->orderBy($this->titleField($collection))->paginate(20);

        return view('admin.home-content.index', compact('items', 'collection') + ['label' => $settings['label']]);
    }

    public function create(string $collection): View
    {
        $settings = $this->settings($collection);
        $item = new ($settings['model']);
        $item->fill(['is_active' => true, 'show_on_home' => true, 'bg_color' => '#EAF6F0']);

        return view('admin.home-content.form', compact('item', 'collection') + ['label' => $settings['label'], 'fields' => $this->formFields($settings['fields'], $item)]);
    }

    public function store(Request $request, string $collection): RedirectResponse
    {
        $settings = $this->settings($collection);
        $data = $this->validated($request, $collection);
        $model = $settings['model'];
        $model::create($data);

        return redirect()->route('admin.home-content.index', $collection)->with('success', $settings['label'].' item created.');
    }

    public function edit(string $collection, int $id): View
    {
        $settings = $this->settings($collection);
        $item = $settings['model']::query()->findOrFail($id);

        return view('admin.home-content.form', compact('item', 'collection') + ['label' => $settings['label'], 'fields' => $this->formFields($settings['fields'], $item)]);
    }

    public function update(Request $request, string $collection, int $id): RedirectResponse
    {
        $settings = $this->settings($collection);
        $item = $settings['model']::query()->findOrFail($id);
        $item->update($this->validated($request, $collection, $item));

        return back()->with('success', $settings['label'].' item updated.');
    }

    public function destroy(string $collection, int $id): RedirectResponse
    {
        $settings = $this->settings($collection);
        $settings['model']::query()->findOrFail($id)->delete();

        return redirect()->route('admin.home-content.index', $collection)->with('success', $settings['label'].' item deleted.');
    }

    private function validated(Request $request, string $collection, ?Model $item = null): array
    {
        $settings = $this->settings($collection);
        $rules = $settings['rules'];

        if ($collection === 'content-blocks') {
            $rules['key'] = ['required', 'string', 'max:255', Rule::unique('content_blocks', 'key')->ignore($item?->getKey())];
        }
        if ($collection === 'team-members') {
            $rules['slug'] = ['required', 'string', 'max:255', Rule::unique('team_members', 'slug')->ignore($item?->getKey())];
        }

        $data = $request->validate($rules);
        if ($collection === 'content-blocks') {
            $data['buttons'] = json_decode($data['buttons_json'] ?? '[]', true) ?: null;
            unset($data['buttons_json']);
        }

        foreach (['is_active', 'show_on_home'] as $toggle) {
            if (array_key_exists($toggle, $rules)) {
                $data[$toggle] = $request->boolean($toggle);
            }
        }
        if (array_key_exists('sort_order', $rules) && ! array_key_exists('sort_order', $data)) {
            $data['sort_order'] = 0;
        }

        return $data;
    }

    private function settings(string $collection): array
    {
        abort_unless(isset(self::COLLECTIONS[$collection]), 404);

        return self::COLLECTIONS[$collection];
    }

    private function titleField(string $collection): string
    {
        return match ($collection) {
            'features', 'content-blocks' => 'title',
            'success-videos' => 'title',
            'testimonials', 'team-members', 'partners' => 'name',
        };
    }

    private function formFields(array $fields, Model $item): array
    {
        foreach ($fields as &$field) {
            $field['value'] = $field['type'] === 'json'
                ? json_encode($item->buttons ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                : ($item->{$field['name']} ?? null);
        }

        return $fields;
    }
}

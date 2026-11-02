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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Dashboard CRUD for the small home page collections (why-choose cards, team, testimonials ...).
 * One controller for all of them: each collection is described in COLLECTIONS
 * (model, form fields, validation rules) and the same views draw the list and the form.
 *
 * Field types: text, textarea, url, number, color, toggle, image (upload or path), repeater (rows → JSON column)
 */
class HomeContentController extends Controller
{
    private const COLLECTIONS = [
        'features' => [
            'label' => 'Why choose us cards', 'model' => Feature::class, 'title' => 'title',
            'fields' => [
                'title'      => ['label' => 'Title', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'body'       => ['label' => 'Description (HTML: <p>, <strong>, <ul><li>)', 'type' => 'textarea', 'rules' => ['required', 'string']],
                'icon'       => ['label' => 'Icon picture', 'type' => 'image'],
                'bg_color'   => ['label' => 'Card background', 'type' => 'color', 'rules' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/']],
                'sort_order' => ['label' => 'Display order', 'type' => 'number'],
                'is_active'  => ['label' => 'Visible on home page', 'type' => 'toggle'],
            ],
        ],
        'content-blocks' => [
            'label' => 'Specialty blocks', 'model' => ContentBlock::class, 'title' => 'title',
            'fields' => [
                'key'     => ['label' => 'Block key', 'type' => 'text', 'rules' => ['required', 'string', 'max:255'], 'hint' => 'home.specialty or home.trusted are used on the home page.'],
                'eyebrow' => ['label' => 'Small heading', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'title'   => ['label' => 'Title', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'body'    => ['label' => 'Description (HTML: <p>, <strong>)', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
                'image'   => ['label' => 'Picture', 'type' => 'image'],
                'buttons' => ['label' => 'Buttons', 'type' => 'repeater', 'fields' => ['label' => 'Text', 'url' => 'Link']],
            ],
        ],
        'success-videos' => [
            'label' => 'Success videos', 'model' => SuccessVideo::class, 'title' => 'title',
            'fields' => [
                'title'       => ['label' => 'Title', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'youtube_url' => ['label' => 'YouTube link', 'type' => 'url', 'rules' => ['required', 'url', 'max:255']],
                'sort_order'  => ['label' => 'Display order', 'type' => 'number'],
                'is_active'   => ['label' => 'Visible on home page', 'type' => 'toggle'],
            ],
        ],
        'testimonials' => [
            'label' => 'Testimonials', 'model' => Testimonial::class, 'title' => 'name',
            'fields' => [
                'name'        => ['label' => 'Student name', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'course_name' => ['label' => 'Course', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'batch'       => ['label' => 'Batch', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'message'     => ['label' => 'Feedback', 'type' => 'textarea', 'rules' => ['required', 'string']],
                'photo'       => ['label' => 'Photo', 'type' => 'image'],
                'sort_order'  => ['label' => 'Display order', 'type' => 'number'],
                'is_active'   => ['label' => 'Visible on home page', 'type' => 'toggle'],
            ],
        ],
        'team-members' => [
            'label' => 'Support team', 'model' => TeamMember::class, 'title' => 'name',
            'fields' => [
                'name'         => ['label' => 'Name', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'slug'         => ['label' => 'Slug (for a profile page later)', 'type' => 'text', 'rules' => ['nullable', 'alpha_dash', 'max:255'], 'hint' => 'Leave empty to make it from the name.'],
                'designation'  => ['label' => 'Role', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'bio'          => ['label' => 'Biography', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
                'photo'        => ['label' => 'Photo', 'type' => 'image'],
                'sort_order'   => ['label' => 'Display order', 'type' => 'number'],
                'show_on_home' => ['label' => 'Show on home page', 'type' => 'toggle'],
                'is_active'    => ['label' => 'Active', 'type' => 'toggle'],
            ],
        ],
        'partners' => [
            'label' => 'Partners', 'model' => Partner::class, 'title' => 'name',
            'fields' => [
                'name'       => ['label' => 'Name', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'logo'       => ['label' => 'Logo', 'type' => 'image', 'required' => true],
                'website'    => ['label' => 'Website', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
                'sort_order' => ['label' => 'Display order', 'type' => 'number'],
                'is_active'  => ['label' => 'Visible on home page', 'type' => 'toggle'],
            ],
        ],
    ];

    public function index(string $collection): View
    {
        $settings = $this->settings($collection);
        $query = $settings['model']::query();

        if (isset($settings['fields']['sort_order'])) {
            $query->orderBy('sort_order');
        }

        return view('admin.home-content.index', [
            'collection' => $collection,
            'label'      => $settings['label'],
            'titleField' => $settings['title'],
            'imageField' => $this->imageFields($collection)[0] ?? null,
            'items'      => $query->orderBy($settings['title'])->paginate(20),
        ]);
    }

    public function create(string $collection): View
    {
        $settings = $this->settings($collection);
        $item = new ($settings['model']);
        $item->forceFill(array_intersect_key(
            ['is_active' => true, 'show_on_home' => true, 'bg_color' => '#EAF6F0', 'sort_order' => 0],
            $settings['fields'],
        ));

        return $this->form($collection, $item);
    }

    public function store(Request $request, string $collection): RedirectResponse
    {
        $settings = $this->settings($collection);
        $settings['model']::create($this->validated($request, $collection));

        return redirect()->route('admin.home-content.index', $collection)->with('success', $settings['label'].': item added.');
    }

    public function edit(string $collection, int $id): View
    {
        return $this->form($collection, $this->settings($collection)['model']::findOrFail($id));
    }

    public function update(Request $request, string $collection, int $id): RedirectResponse
    {
        $settings = $this->settings($collection);
        $item = $settings['model']::findOrFail($id);
        $item->update($this->validated($request, $collection, $item));

        return back()->with('success', $settings['label'].': item updated.');
    }

    public function destroy(string $collection, int $id): RedirectResponse
    {
        $settings = $this->settings($collection);
        $item = $settings['model']::findOrFail($id);

        foreach ($this->imageFields($collection) as $field) {
            $this->deleteUpload($collection, $item->{$field});
        }
        $item->delete();

        return redirect()->route('admin.home-content.index', $collection)->with('success', $settings['label'].': item deleted.');
    }

    // ------------------------------------------------------------------

    private function form(string $collection, Model $item): View
    {
        return view('admin.home-content.form', [
            'collection' => $collection,
            'label'      => $this->settings($collection)['label'],
            'fields'     => $this->settings($collection)['fields'],
            'item'       => $item,
        ]);
    }

    private function validated(Request $request, string $collection, ?Model $item = null): array
    {
        $fields = $this->settings($collection)['fields'];
        $rules = [];

        foreach ($fields as $name => $field) {
            $rules[$name] = match ($field['type']) {
                'toggle'   => ['nullable', 'boolean'],
                'number'   => ['nullable', 'integer', 'min:0', 'max:65535'],
                'repeater' => ['nullable', 'json'],
                // An image can be uploaded (file) OR chosen by path (e.g. copied from the Media library)
                'image'    => ['nullable', 'string', 'max:255'],
                default    => $field['rules'],
            };
            if ($field['type'] === 'image') {
                $rules[$name.'_file'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'];
            }
        }

        // Unique columns: allow the item's own value when editing
        if (isset($fields['key'])) {
            $rules['key'][] = Rule::unique('content_blocks', 'key')->ignore($item?->getKey());
        }
        if (isset($fields['slug'])) {
            $rules['slug'][] = Rule::unique('team_members', 'slug')->ignore($item?->getKey());
        }

        $data = $request->validate($rules);

        foreach ($fields as $name => $field) {
            match ($field['type']) {
                'toggle'   => $data[$name] = $request->boolean($name),
                'number'   => $data[$name] = (int) ($data[$name] ?? 0),
                // Rows from <x-admin.repeater>; empty rows are dropped
                'repeater' => $data[$name] = array_values(array_filter(json_decode($data[$name] ?? '[]', true) ?: [], fn ($row) => array_filter($row))) ?: null,
                'image'    => $data[$name] = $this->imageValue($request, $collection, $name, $item),
                default    => null,
            };
            unset($data[$name.'_file']);

            if ($field['type'] === 'image' && ($field['required'] ?? false) && ! $data[$name]) {
                throw ValidationException::withMessages([$name.'_file' => 'Please upload a picture.']);
            }
        }

        // Empty or missing slug → make it from the name ("Rina Akter" → "rina-akter")
        if (isset($fields['slug']) && blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['name'] ?? Str::random(8));
        }

        return $data;
    }

    /**
     * New upload → store it in storage/app/public/uploads/{collection}/ (and remove the old upload).
     * No upload → keep the path typed in the form.
     */
    private function imageValue(Request $request, string $collection, string $field, ?Model $item): ?string
    {
        $old = $item?->{$field};

        if ($request->hasFile($field.'_file')) {
            $this->deleteUpload($collection, $old);

            return $request->file($field.'_file')->store("uploads/{$collection}", 'public');
        }

        $path = $request->input($field);
        if ($path !== $old) {
            $this->deleteUpload($collection, $old);
        }

        return $path ?: null;
    }

    // Only files this controller uploaded are deleted; seeded images (images/...) and Media library files are kept
    private function deleteUpload(string $collection, ?string $path): void
    {
        if ($path && str_starts_with($path, "uploads/{$collection}/")) {
            Storage::disk('public')->delete($path);
        }
    }

    private function imageFields(string $collection): array
    {
        return array_keys(array_filter($this->settings($collection)['fields'], fn ($f) => $f['type'] === 'image'));
    }

    private function settings(string $collection): array
    {
        abort_unless(isset(self::COLLECTIONS[$collection]), 404);

        return self::COLLECTIONS[$collection];
    }
}

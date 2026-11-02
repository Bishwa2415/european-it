<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Helpers for dashboard forms that use <x-admin.repeater> / <x-admin.group-repeater>.
 * Those send one JSON string per list; here it is decoded and every row is validated.
 */
trait ReadsRepeaterJson
{
    /**
     * '[{"label":"Blog","url":"/blog"}]' → validated array of rows.
     * $rules use "*" for a row: ['*.label' => ['required', 'string']]
     * $name is the word shown in error messages, e.g. "Social links".
     * $choiceKeys = dropdown columns (always have a value), not counted when checking if a row is empty.
     */
    protected function rows(?string $json, array $rules, string $name, array $choiceKeys = []): array
    {
        $rows = json_decode($json ?? '[]', true);
        $rows = is_array($rows) ? array_values($rows) : [];

        // Rows the admin added but left empty are ignored
        $rows = array_values(array_filter($rows, fn ($row) => is_array($row)
            && collect($row)->except($choiceKeys)->filter(fn ($v) => trim((string) $v) !== '')->isNotEmpty()));

        $prefixed = collect($rules)->mapWithKeys(fn ($rule, $key) => ["{$name}.{$key}" => $rule])->all();

        return validator([$name => $rows], [$name => ['array']] + $prefixed)->validate()[$name] ?? [];
    }

    /** ["+880 1...", ...]: a repeater with one "value" column → plain list of strings */
    protected function values(?string $json, string $name, array $rule = ['string', 'max:255']): array
    {
        return array_column($this->rows($json, ['*.value' => ['required', ...$rule]], $name), 'value');
    }

    /**
     * Groups from <x-admin.group-repeater>: [{"title": "...", "items": [...]}].
     * Every group's rows are validated with $rules (same format as rows()).
     */
    protected function groups(?string $json, array $rules, string $name, bool $needsTitle = true): array
    {
        $groups = json_decode($json ?? '[]', true);
        $groups = is_array($groups) ? array_values($groups) : [];

        validator([$name => $groups], [
            $name => ['array'],
            "{$name}.*.title" => $needsTitle ? ['required', 'string', 'max:120', 'distinct'] : ['nullable'],
        ])->validate();

        return array_map(fn ($group, $i) => [
            'title' => trim((string) ($group['title'] ?? '')),
            'items' => $this->rows(json_encode($group['items'] ?? []), $rules, $name.' group '.($i + 1), ['badge']),
        ], $groups, array_keys($groups));
    }

    /** A new uploaded file wins over the typed path; the old upload in $folder is deleted */
    protected function storeImage(?UploadedFile $file, ?string $path, ?string $oldPath, string $folder): ?string
    {
        if (! $file) {
            return $path;
        }

        if ($oldPath && str_starts_with($oldPath, $folder.'/')) {
            Storage::disk('public')->delete($oldPath);
        }

        return $file->store($folder, 'public');
    }
}

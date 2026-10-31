{{-- White box with a title. Use: <x-admin.card title="Pricing"> ... </x-admin.card> --}}
@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl bg-white shadow-sm']) }}>
    @if ($title)
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="font-heading font-bold">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-xs text-gray-500">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="space-y-5 p-5">
        {{ $slot }}
    </div>
</div>

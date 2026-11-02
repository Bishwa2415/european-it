@extends('admin.layouts.app')

@section('title', 'Media library')

@section('content')
    <x-admin.card title="Upload pictures" description="JPG, PNG, WEBP or GIF, up to 4 MB each. After uploading, use “Copy path” and paste the path in any image field.">
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
            @csrf
            <input type="file" name="files[]" multiple required accept="image/png,image/jpeg,image/webp,image/gif"
                   class="block text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-primary">
            <button class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-hover">
                <i class="bi bi-cloud-arrow-up"></i> Upload
            </button>
        </form>
        @error('files') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        @error('files.*') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
    </x-admin.card>

    <div class="mt-6 grid gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
        @forelse ($files as $file)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <a href="{{ $file['url'] }}" target="_blank">
                    <img src="{{ $file['url'] }}" alt="" loading="lazy" class="aspect-square w-full bg-gray-50 object-contain">
                </a>
                <div class="space-y-2 p-3 text-xs">
                    <p class="truncate text-gray-600" title="{{ $file['path'] }}">{{ basename($file['path']) }}</p>
                    <p class="text-gray-400">{{ number_format($file['size'] / 1024) }} KB</p>
                    <div class="flex gap-2">
                        <button type="button" data-copy="{{ $file['path'] }}" class="flex-1 rounded-md bg-primary/10 py-1 text-primary hover:bg-primary hover:text-white">Copy path</button>
                        <form method="POST" action="{{ route('admin.media.destroy') }}" data-confirm="Delete this picture?">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="path" value="{{ $file['path'] }}">
                            <button title="Delete" class="rounded-md px-2 py-1 text-red-600 hover:bg-red-50"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full rounded-xl bg-white p-10 text-center text-sm text-gray-400 shadow-sm">No pictures uploaded yet.</p>
        @endforelse
    </div>
@endsection

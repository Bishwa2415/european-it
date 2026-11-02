@extends('admin.layouts.app')

@section('title', 'Leads')

{{-- People who booked the free webinar / seminar on a landing page (App\Http\Controllers\Admin\LeadController) --}}
@section('content')
    <form method="GET" class="mb-5 flex flex-wrap items-center gap-3">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Name or phone..."
               class="w-56 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-primary">
        <select name="page_id" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-primary">
            <option value="">All landing pages</option>
            @foreach ($pages as $id => $title)
                <option value="{{ $id }}" @selected(request('page_id') == $id)>{{ $title }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-primary">
            <option value="">All</option>
            <option value="new" @selected(request('status') === 'new')>Not contacted</option>
            <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
        </select>
        <button class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-hover">Filter</button>
        <span class="ml-auto text-sm text-gray-500">{{ $leads->total() }} leads</span>
        <a href="{{ route('admin.leads.export', request()->query()) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:text-primary">
            <i class="bi bi-download"></i> CSV
        </a>
    </form>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Landing page</th>
                    <th class="px-4 py-3 text-center">Contacted</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($leads as $lead)
                    <tr @class(['hover:bg-gray-50', 'text-gray-400' => $lead->is_contacted])>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $lead->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $lead->name }}</td>
                        <td class="px-4 py-3"><a href="tel:{{ $lead->phone }}" class="text-primary hover:underline">{{ $lead->phone }}</a></td>
                        <td class="px-4 py-3">{{ $lead->landingPage?->title ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
                                @csrf @method('PATCH')
                                <button title="Mark contacted / not contacted" class="text-xl {{ $lead->is_contacted ? 'text-green-600' : 'text-gray-300 hover:text-green-600' }}">
                                    <i class="bi bi-check-circle-fill"></i>
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" data-confirm="Delete this lead?">
                                @csrf @method('DELETE')
                                <button class="text-gray-400 hover:text-red-600"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500">No leads yet. They appear here when someone books on a landing page.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $leads->links() }}</div>
@endsection

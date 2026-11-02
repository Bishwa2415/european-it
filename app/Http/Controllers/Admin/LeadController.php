<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Dashboard → Leads: people who booked the free webinar / seminar on a landing page.
 * The team calls them and ticks "Contacted". CSV download for Excel.
 */
class LeadController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.leads.index', [
            'leads' => $this->filtered($request)->with('landingPage')->latest()->paginate(30)->withQueryString(),
            'pages' => LandingPage::orderBy('title')->pluck('title', 'id'),
        ]);
    }

    // PATCH: tick / untick "Contacted"
    public function update(Lead $lead): RedirectResponse
    {
        $lead->update(['is_contacted' => ! $lead->is_contacted]);

        return back();
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('success', 'Lead deleted.');
    }

    // GET /admin/leads/export → leads.csv (same filters as the list)
    public function export(Request $request): StreamedResponse
    {
        $leads = $this->filtered($request)->with('landingPage')->latest()->get();

        return response()->streamDownload(function () use ($leads) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");   // BOM: Excel then shows Bangla correctly
            fputcsv($out, ['Date', 'Name', 'Phone', 'Landing page', 'Contacted']);
            foreach ($leads as $lead) {
                // A leading "=", "+", "-" or "@" would run as a formula in Excel: add a quote in front
                $safe = fn (?string $v) => preg_match('/^[=+\-@]/', (string) $v) ? "'".$v : $v;
                fputcsv($out, [$lead->created_at->format('Y-m-d H:i'), $safe($lead->name), $safe($lead->phone), $lead->landingPage?->title, $lead->is_contacted ? 'yes' : 'no']);
            }
            fclose($out);
        }, 'leads-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filtered(Request $request)
    {
        return Lead::query()
            ->when($request->page_id, fn ($q, $id) => $q->where('landing_page_id', $id))
            ->when($request->status === 'new', fn ($q) => $q->where('is_contacted', false))
            ->when($request->status === 'contacted', fn ($q) => $q->where('is_contacted', true))
            ->when($request->search, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('phone', 'like', "%{$s}%")));
    }
}

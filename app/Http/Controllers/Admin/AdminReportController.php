<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $q      = $request->query('q');

        $query = Report::query()->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('target_name', 'like', "%{$q}%")
                    ->orWhere('target_url', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('reporter_email', 'like', "%{$q}%");
            });
        }

        $reports = $query->paginate(15)->withQueryString();

        $counts = [
            'pending'   => Report::where('status', 'pending')->count(),
            'reviewed'  => Report::where('status', 'reviewed')->count(),
            'resolved'  => Report::where('status', 'resolved')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
            'all'       => Report::count(),
        ];

        return view('admin.reports.index', compact('reports', 'status', 'counts'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status'      => 'required|string|in:pending,reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update($validated);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}

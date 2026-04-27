<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Project;
use App\Models\SuratJalan;
use App\Models\DetailSuratJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    public function index()
    {
        $projects = Project::withCount([
            'suratJalan as sj_count' => fn($q) => $q->whereNull('deleted_at')
        ])->orderBy('created_at')->get();

        return view('surat-jalan.list', compact('projects'));
    }

    public function getBySuratJalanJson(Request $request)
    {
        $query = SuratJalan::with(['user', 'project'])->whereNull('deleted_at');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        } elseif ($request->boolean('unassigned')) {
            $query->whereNull('project_id');
        } elseif (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $results = $query->orderByDesc('id')->get()->map(fn($sj) => [
            'id'             => $sj->id,
            'no_surat_jalan' => $sj->no_surat_jalan,
            'tanggal'        => $sj->tanggal?->format('Y-m-d'),
            'tujuan'         => $sj->tujuan,
            'status'         => $sj->status,
            'creator'        => $sj->user?->username ?? '-',
            'project_name'   => $sj->project?->name ?? null,
        ]);

        return response()->json($results);
    }

    public function create()
    {
        $barang   = Barang::orderBy('nama_barang')->get();
        $projects = Project::orderBy('name')->get();
        return view('surat-jalan.form', compact('barang', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'  => 'required|date',
            'tujuan'   => 'required|string',
            'items'    => 'required|array|min:1',
        ]);

        try {
            $user   = auth()->user();
            $status = $user->isAdmin() ? 'APPROVED' : 'PENDING';

            // Auto-numbering
            $now    = Carbon::now();
            $mm     = $now->format('m');
            $yyyy   = $now->year;
            $suffix = "/{$mm}/BPI/{$yyyy}";

            $manualNo = trim($request->input('manual_no', ''));
            if ($manualNo !== '') {
                if (SuratJalan::where('no_surat_jalan', $manualNo)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => "No. Surat Jalan \"{$manualNo}\" sudah ada di database.",
                    ], 422);
                }
                $noSJ = $manualNo;
            } else {
                // Fetch all matching SJ numbers and find the max in PHP
                // Avoids CAST/SPLIT_PART raw SQL that can corrupt Neon pgBouncer connections
                $existing = SuratJalan::withTrashed()
                    ->where('no_surat_jalan', 'LIKE', "%{$suffix}")
                    ->pluck('no_surat_jalan');

                $maxNum = 0;
                foreach ($existing as $noSj) {
                    $parts = explode(' ', $noSj);
                    $num   = (int) ($parts[0] ?? 0);
                    if ($num > $maxNum) $maxNum = $num;
                }

                $noSJ = str_pad($maxNum + 1, 3, '0', STR_PAD_LEFT) . " {$suffix}";
            }

            DB::transaction(function () use ($request, $user, $status, $noSJ) {
                $sj = SuratJalan::create([
                    'no_surat_jalan' => $noSJ,
                    'tanggal'        => $request->tanggal,
                    'tujuan'         => $request->tujuan,
                    'attn'           => $request->attn,
                    'phone_header'   => $request->phone_header,
                    'note'           => $request->note,
                    'taken_by'       => $request->taken_by,
                    'vehicle_no'     => $request->vehicle_no,
                    'phone_footer'   => $request->phone_footer,
                    'eta'            => $request->eta ?: null,
                    'foreman'        => $request->foreman,
                    'woc'            => $request->woc,
                    'status'         => $status,
                    'user_id'        => $user->id,
                    'project_id'     => $request->project_id ?: null,
                ]);

                foreach ($request->items as $index => $item) {
                    DetailSuratJalan::create([
                        'surat_jalan_id'   => $sj->id,
                        'type'             => $item['type'],
                        'group_title_text' => $item['type'] === 'group_title' ? ($item['text'] ?? null) : null,
                        'barang_id'        => $item['type'] === 'item' ? ($item['id'] ?? null) : null,
                        'qty'              => $item['type'] === 'item' ? ($item['qty'] ?? null) : null,
                        'remark'           => $item['type'] === 'item' ? ($item['remark'] ?? null) : null,
                        'order_index'      => $index,
                    ]);
                }
            });

            // Set session AFTER transaction commits (not inside — avoids any session-DB interaction)
            session(['last_no_sj' => $noSJ, 'last_sj_status' => $status]);

            return response()->json([
                'success'        => true,
                'no_surat_jalan' => $noSJ,
                'status'         => $status,
            ]);

        } catch (\Throwable $e) {
            \Log::error('SuratJalan store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $sj = SuratJalan::withTrashed()
            ->with(['user', 'project', 'deletedByUser', 'details.barang'])
            ->findOrFail($id);

        return view('surat-jalan.print', compact('sj'));
    }

    public function updateStatus(Request $request, SuratJalan $suratJalan)
    {
        $request->validate([
            'status' => 'required|in:APPROVED,REJECTED',
        ]);

        $suratJalan->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status berhasil diubah.']);
    }

    public function destroy(SuratJalan $suratJalan)
    {
        $suratJalan->update(['deleted_by' => auth()->id()]);
        $suratJalan->delete();

        return response()->json(['success' => true, 'message' => 'Surat Jalan berhasil dihapus.']);
    }

    public function deleted()
    {
        $deleted = SuratJalan::onlyTrashed()
            ->with(['user', 'project', 'deletedByUser'])
            ->orderByDesc('deleted_at')
            ->get()
            ->map(fn($sj) => [
                'id'             => $sj->id,
                'no_surat_jalan' => $sj->no_surat_jalan,
                'tanggal'        => $sj->tanggal?->format('Y-m-d'),
                'tujuan'         => $sj->tujuan,
                'project_name'   => $sj->project?->name ?? null,
                'creator'        => $sj->user?->username ?? '-',
                'deleted_by_name' => $sj->deletedByUser?->username ?? '-',
                'deleted_at'     => $sj->deleted_at?->toIso8601String(),
            ]);

        return response()->json($deleted);
    }

    public function exportPdf(SuratJalan $suratJalan)
    {
        $suratJalan->load(['details.barang']);
        
        $logoPath = public_path('img/bauer-logo.jpeg');
        $logoSrc  = file_exists($logoPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat-jalan.export-batch', [
                'suratJalans' => collect([$suratJalan]),
                'logoSrc'     => $logoSrc,
            ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'Helvetica',
            ]);

        $cleanNoSj = str_replace(['/', '\\'], '-', $suratJalan->no_surat_jalan);
        $fileName  = \Illuminate\Support\Str::slug($cleanNoSj) . '.pdf';
        
        return $pdf->download($fileName);
    }
}

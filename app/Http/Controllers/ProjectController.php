<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SuratJalan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use ZipArchive;


class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount([
            'suratJalan as sj_count' => fn($q) => $q->whereNull('deleted_at')
        ])->orderBy('created_at')->get();

        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:projects,name']);

        $project = Project::create(['name' => $request->name]);
        $project->sj_count = 0;

        return response()->json(['success' => true, 'project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|unique:projects,name,' . $project->id,
        ]);

        $project->update(['name' => $request->name]);

        return response()->json(['success' => true, 'message' => 'Project berhasil diubah.']);
    }

    public function destroy(Project $project)
    {
        $activeCount = SuratJalan::where('project_id', $project->id)->count();
        if ($activeCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Folder tidak dapat dihapus karena masih memiliki {$activeCount} Surat Jalan aktif.",
            ], 422);
        }

        $project->delete();
        return response()->json(['success' => true, 'message' => 'Project berhasil dihapus.']);
    }

    public function exportZip(Project $project)
    {
        $suratJalans = SuratJalan::where('project_id', $project->id)
            ->whereNull('deleted_at')
            ->where('status', 'APPROVED')
            ->with(['details.barang'])
            ->get();

        if ($suratJalans->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada Surat Jalan yang disetujui (APPROVED) untuk di-export.',
            ], 422);
        }

        $zip         = new ZipArchive();
        $zipFileName = 'Export-' . Str::slug($project->name) . '-' . date('Ymd-His') . '.zip';
        $zipPath     = storage_path('app/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat file ZIP.'], 500);
        }

        foreach ($suratJalans as $sj) {
            // Use CDN-free template so DomPDF doesn't hang on network requests
            $logoPath = public_path('img/bauer-logo.jpeg');
            $logoSrc  = file_exists($logoPath)
                ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
                : null;

            $pdf = Pdf::loadView('surat-jalan.export-batch', [
                    'suratJalans' => collect([$sj]),
                    'logoSrc'     => $logoSrc,
                ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => false,
                    'defaultFont'          => 'DejaVu Sans',
                ]);

            $cleanNoSj   = str_replace(['/', '\\'], '-', $sj->no_surat_jalan);
            $pdfFileName = Str::slug($cleanNoSj) . '.pdf';
            $zip->addFromString($pdfFileName, $pdf->output());
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}


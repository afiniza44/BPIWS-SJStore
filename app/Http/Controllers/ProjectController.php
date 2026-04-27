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
                'message' => 'Tidak ada Surat Jalan yang disetujui (APPROVED) untuk di-export.'
            ], 422);
        }

        $zip = new ZipArchive();
        $zipFileName = 'Export-' . Str::slug($project->name) . '-' . date('Ymd-His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($suratJalans as $sj) {
                // Load PDF with specific options for better layout in dompdf
                $pdf = Pdf::loadView('surat-jalan.print', ['sj' => $sj])
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

                $cleanNoSj = str_replace(['/', '\\'], '-', $sj->no_surat_jalan);
                $pdfFileName = Str::slug($cleanNoSj) . '.pdf';
                
                $zip->addFromString($pdfFileName, $pdf->output());
            }
            $zip->close();
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal membuat file ZIP.'], 500);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}


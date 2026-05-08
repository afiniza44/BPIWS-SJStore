<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::orderBy('id')->get();
        return view('barang.index', compact('barang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku'         => 'required|string|unique:master_barang,sku',
            'nama_barang' => 'required|string',
            'satuan'      => 'required|string',
        ]);

        Barang::create($data);

        return response()->json(['success' => true, 'message' => 'Barang berhasil ditambahkan.']);
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'sku'         => 'required|string|unique:master_barang,sku,' . $barang->id,
            'nama_barang' => 'required|string',
            'satuan'      => 'required|string',
        ]);

        $barang->update($data);

        return response()->json(['success' => true, 'message' => 'Barang berhasil diubah.']);
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return response()->json(['success' => true, 'message' => 'Barang berhasil dihapus.']);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array|min:1']);
        Barang::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => count($request->ids) . ' barang berhasil dihapus.']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:102400',
        ]);

        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        try {
            $inserted = 0;
            $skipped = 0;
            $errors = [];
            $incomingSkus = [];
            
            $file = $request->file('file');
            // Read rows efficiently using SimpleExcelReader (uses <2MB RAM)
            // Explicitly pass 'xlsx' or the extension because the temp path has no extension
            $rows = \Spatie\SimpleExcel\SimpleExcelReader::create($file->getPathname(), $file->getClientOriginalExtension())
                ->getRows();
            
            $validRows = [];
            
            foreach ($rows as $index => $row) {
                // $index starts from 0 for the first data row
                $row = array_change_key_case($row, CASE_LOWER);

                $sku        = trim((string)($row['sku']         ?? ''));
                $namaBarang = trim((string)($row['nama_barang'] ?? ''));
                $satuan     = trim((string)($row['satuan']      ?? ''));

                if (!$sku || !$namaBarang || !$satuan) {
                    $errors[] = [
                        'row'    => $index + 2, // Header is row 1
                        'sku'    => $sku ?: '(kosong)',
                        'reason' => 'Kolom SKU, nama_barang, atau satuan kosong.',
                    ];
                    continue;
                }

                if (isset($incomingSkus[$sku])) {
                    $skipped++;
                    continue;
                }

                $incomingSkus[$sku] = true;
                $validRows[] = [
                    'sku'         => $sku,
                    'nama_barang' => $namaBarang,
                    'satuan'      => $satuan,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            if (!empty($validRows)) {
                $incomingSkuList = array_column($validRows, 'sku');
                $existingSkus = Barang::whereIn('sku', $incomingSkuList)->pluck('sku')->flip()->all();

                $toInsert = [];
                foreach ($validRows as $row) {
                    if (isset($existingSkus[$row['sku']])) {
                        $skipped++;
                    } else {
                        $toInsert[] = $row;
                    }
                }

                if (!empty($toInsert)) {
                    foreach (array_chunk($toInsert, 500) as $chunk) {
                        Barang::insert($chunk);
                        $inserted += count($chunk);
                    }
                }
            }

            return response()->json([
                'success'  => true,
                'inserted' => $inserted,
                'skipped'  => $skipped,
                'errors'   => $errors,
                'message'  => "Import selesai. {$inserted} data ditambahkan, {$skipped} dilewati (SKU duplikat).",
            ]);
        } catch (\Throwable $e) {
            \Log::error('Import error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Import gagal: ' . $e->getMessage()], 422);
        }
    }
}

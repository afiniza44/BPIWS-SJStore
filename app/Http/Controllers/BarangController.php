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

        try {
            $import = new BarangImport();
            Excel::import($import, $request->file('file'));

            return response()->json([
                'success'  => true,
                'inserted' => $import->inserted,
                'skipped'  => $import->skipped,
                'errors'   => $import->errors,
                'message'  => "Import selesai. {$import->inserted} data ditambahkan, {$import->skipped} dilewati (SKU duplikat).",
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Import gagal: ' . $e->getMessage()], 422);
        }
    }
}

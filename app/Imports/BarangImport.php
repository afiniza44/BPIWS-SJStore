<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BarangImport implements ToArray, WithHeadingRow, WithChunkReading
{
    public int $inserted = 0;
    public int $skipped  = 0;
    public array $errors = [];

    public function array(array $rows): void
    {
        // ─── 1. Normalize & validate all rows first ───────────────────────────
        $validRows  = [];
        $incomingSkus = [];

        foreach ($rows as $index => $row) {
            if (!is_array($row)) continue;
            $row = array_change_key_case($row, CASE_LOWER);

            $sku        = trim((string)($row['sku']         ?? ''));
            $namaBarang = trim((string)($row['nama_barang'] ?? ''));
            $satuan     = trim((string)($row['satuan']      ?? ''));

            if (!$sku || !$namaBarang || !$satuan) {
                $this->errors[] = [
                    'row'    => $index + 2,
                    'sku'    => $sku ?: '(kosong)',
                    'reason' => 'Kolom SKU, nama_barang, atau satuan kosong.',
                ];
                continue;
            }

            // Deduplicate within the file itself
            if (isset($incomingSkus[$sku])) {
                $this->skipped++;
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

        if (empty($validRows)) {
            return;
        }

        // ─── 2. Fetch existing SKUs in one query ──────────────────────────────
        $incomingSkuList  = array_column($validRows, 'sku');
        $existingSkus     = Barang::whereIn('sku', $incomingSkuList)
                                   ->pluck('sku')
                                   ->flip()
                                   ->all(); // ['SKU-001' => 0, ...]

        $toInsert = [];
        foreach ($validRows as $row) {
            if (isset($existingSkus[$row['sku']])) {
                $this->skipped++;
            } else {
                $toInsert[] = $row;
            }
        }

        if (empty($toInsert)) {
            return;
        }

        // ─── 3. Bulk insert in chunks of 500 — ONE round-trip per chunk ───────
        foreach (array_chunk($toInsert, 500) as $chunk) {
            Barang::insert($chunk);
            $this->inserted += count($chunk);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

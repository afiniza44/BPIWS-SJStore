<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSuratJalan extends Model
{
    protected $table = 'detail_surat_jalan';

    protected $fillable = [
        'surat_jalan_id', 'type', 'group_title_text',
        'manual_asset_id', 'manual_nama_barang', 'manual_satuan',
        'barang_id', 'qty', 'remark', 'order_index',
    ];

    public function suratJalan()
    {
        return $this->belongsTo(SuratJalan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}

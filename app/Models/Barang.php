<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'master_barang';

    protected $fillable = ['sku', 'nama_barang', 'satuan'];

    public function detailSuratJalan()
    {
        return $this->hasMany(DetailSuratJalan::class, 'barang_id');
    }
}

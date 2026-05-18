<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sku
 * @property string $nama_barang
 * @property string $satuan
 */
class Barang extends Model
{
    protected $table = 'master_barang';

    protected $fillable = ['sku', 'nama_barang', 'satuan'];

    public function detailSuratJalan()
    {
        return $this->hasMany(DetailSuratJalan::class, 'barang_id');
    }
}

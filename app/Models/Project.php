<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Project extends Model
{
    protected $fillable = ['name', 'delivery_to', 'attn', 'phone_header'];

    public function suratJalan()
    {
        return $this->hasMany(SuratJalan::class);
    }
}

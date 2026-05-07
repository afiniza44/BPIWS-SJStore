<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratJalan extends Model
{
    use SoftDeletes;

    protected $table = 'surat_jalan';

    protected $fillable = [
        'no_surat_jalan', 'tanggal', 'tujuan', 'attn', 'phone_header',
        'note', 'taken_by', 'vehicle_no', 'phone_footer', 'eta',
        'foreman', 'woc', 'user_id', 'project_id', 'deleted_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'eta'     => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function deletedByUser()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function details()
    {
        return $this->hasMany(DetailSuratJalan::class)->orderBy('order_index');
    }
}

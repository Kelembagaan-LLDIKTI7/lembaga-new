<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class SkKumham extends Model
{
    use HasFactory, Notifiable, HasUuids, HasRoles;

    protected $guarded = [
        'id',
        'kumham_nomor',
        'kumham_perihal',
        'kumham_tanggal',
        'kumham_dokumen',
        'id_akta',
        'created_at',
        'updated_at',
    ];

    public function akta()
    {
        return $this->belongsTo(Akta::class, 'id_akta', 'id');
    }
}

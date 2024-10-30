<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class SuratKeputusan extends Model
{
    use HasFactory, Notifiable, HasUuids, HasRoles;

    protected $guarded = [
        'id',
    ];

    public function jenisSuratKeputusan()
    {
        return $this->belongsTo(JenisSuratKeputusan::class, 'id_jenis_surat_keputusan', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organisasi::class, 'id_organization', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id');
    }

    public function lembaga()
    {
        return $this->belongsTo(LembagaAkreditasi::class, 'lembaga_akreditasi_id', 'id');
    }

    public function peringkat()
    {
        return $this->belongsTo(PeringkatAkreditasi::class, 'peringkat_akreditasi_id', 'id');
    }
}

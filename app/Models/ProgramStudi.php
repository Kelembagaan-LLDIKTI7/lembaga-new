<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProgramStudi extends Model
{
    use HasFactory, Notifiable, HasUuids;

    protected $guarded = [
        'id',
    ];

    public function perguruanTinggi()
    {
        return $this->belongsTo(Organisasi ::class, 'id_organization', 'id');
    }

    public function historiPerguruanTinggi()
    {
        return $this->hasMany(HistoryProgramStudi ::class, 'id_prodi', 'id');
    }

    public function suratKeputusan()
    {
        return $this->hasMany(SuratKeputusan::class, 'id_prodi', 'id');
    }

    public function akreditasis()
    {
        return $this->hasMany(Akreditasi::class, 'id_prodi', 'id');
    }

    public function perkaras()
    {
        return $this->hasMany(Perkara::class, 'id_organization', 'id');
    }

    public function prodistatus()
    {
        return $this->belongsTo(ProdiStatus::class, 'prodi_active_status', 'id');
    }
}

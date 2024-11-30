<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class HistoriPt extends Model
{
    use HasFactory, Notifiable, HasUuids, HasRoles;

    protected $guarded = [
        'id',
    ];

    public function perguruanTinggi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organization', 'id');
    }

    public function bpLama()
    {
        return $this->belongsTo(Organisasi::class, 'parent_id_lama', 'id');
    }

    public function bpBaru()
    {
        return $this->belongsTo(Organisasi::class, 'parent_id_baru', 'id');
    }
}

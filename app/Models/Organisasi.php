<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Organisasi extends Model
{
    use HasFactory, Notifiable, HasUuids, HasRoles;

    protected $guarded = [
        'id',
    ];

    public function parent()
    {
        return $this->belongsTo(Organisasi::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Organisasi::class, 'parent_id', 'id');
    }

    public function berubah()
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_berubah_id', 'id');
    }

    public function changed()
    {
        return $this->hasMany(Organisasi::class, 'organisasi_berubah_id', 'id');
    }

    public function prodis()
    {
        return $this->hasMany(ProgramStudi::class, 'id_organization', 'id');
    }

    public function organizationType()
    {
        return $this->belongsTo(OrganisasiType::class, 'organisasi_type_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(Organisasi::class, 'id_organization', 'id');
    }

    public function akreditasis()
    {
        return $this->hasMany(Akreditasi::class, 'id_organization', 'id');
    }

    public function akta()
    {
        return $this->hasMany(Akta::class, 'id_organization', 'id');
    }

    public function bentukPT()
    {
        return $this->belongsTo(BentukPt::class, 'organisasi_bentuk_pt', 'id');
    }

    public function dari()
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_berubah_status', 'id');
    }

    public function referensi()
    {
        return $this->hasMany(Organisasi::class, 'organisasi_berubah_status', 'id');
    }
}

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
        return $this->belongsTo(Organisasi ::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Organisasi ::class, 'parent_id', 'id');
    }

    public function berubah()
    {
        return $this->belongsTo(Organisasi ::class, 'organisasi_berubah_id', 'id');
    }

    public function changed()
    {
        return $this->hasMany(Organisasi ::class, 'organisasi_berubah_id', 'id');
    }
}

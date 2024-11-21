<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class OrganisasiType extends Model
{
    use HasFactory, Notifiable, HasRoles;

    protected $guarded = [
        'id',
    ];

    public function organization()
    {
        return $this->hasMany(Organisasi::class, 'org_type_id', 'id');
    }
}

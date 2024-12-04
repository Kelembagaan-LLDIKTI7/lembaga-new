<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProdiStatus extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [
        'id',
    ];

    public function programStudis()
    {
        return $this->hasMany(ProgramStudi::class, 'prodi_active_status', 'id');
    }

    public function historyprogramStudis()
    {
        return $this->hasMany(HistoryProgramStudi::class, 'prodi_active_status', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'captain_id',
        'number',
        'name',
    ];

    protected $visible = [
        'id',
        'name',
        'number',
        'captain'
    ];

    public function captain()
    {
        return $this->belongsTo(Captain::class);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = "trip_offers";

    protected $fillable = [
        'trip_id',
        'captain_id',
        'amount',
        'accepted'
    ];

    public function captain()
    {
        return $this->belongsTo(Captain::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }


}

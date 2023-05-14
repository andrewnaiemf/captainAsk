<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public $fillable = ['user_id','trip_id','rating','feedback','rated_by_customer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function captain()
    {
        return $this->belongsTo(Cptain::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function ratedByCustomer()
    {
        return $this->belongsTo(User::class, 'rated_by_customer_id');
    }

}

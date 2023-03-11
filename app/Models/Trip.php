<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $appends = ['rating_customer','rating_captain'];

    protected $hidden = ['rating'];

    public function rating()
    {
        return $this->hasOne(Rating::class, 'trip_id');
    }

    public function getRatingCustomerAttribute()
    {
        if ($this->rating && $this->customer_id) {
            return $this->rating->where([
                'user_id' => $this->customer_id,
                'trip_id' => $this->id
                ])->value('rating');
        }
        return null;
    }


    public function getRatingCaptainAttribute()
    {
        if ($this->rating && $this->captain_id) {
            return $this->rating->where([
                'user_id' => $this->captain_id ,
                 'trip_id' => $this->id
                ])->value('rating');
        }
        return null;
    }


    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function captain()
    {
        return $this->belongsTo(Captain::class);
    }

    public function customerRating()
    {
        $query = $this->hasOne(Rating::class, 'trip_id');

        if ($this->customer) {
            $query->where('user_id', $this->customer()->first()->id)->select('rating');
            $query->withAttribute('rating', function($value) {
                return $value ? $value->rating : null;
            });
        }
        return $query;
    }

    public function captainRating()
    {
        $query = $this->hasOne(Rating::class, 'trip_id');
        if ($this->captain) {
            $query->where('user_id', $this->captain()->first()->id)->select('rating');
            $query->withAttribute('rating', function($value) {
                return $value ? $value->rating : null;
            });
        }
        return $query;
    }


}

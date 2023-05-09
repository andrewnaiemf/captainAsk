<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'status',
        'paymentMethod',
        'captain_id',
        'notes',
        'customer_id',
        'service_id',
        'start_address',
        'start_lat',
        'start_lng',
        'end_lng',
        'end_lat',
        'end_address',
        'cost',
        'paymentMethod',
        'firebaseId',
        'distance',
        'user_notified'
    ];

    public function toArray()
    {
        $tripArray = parent::toArray();
        $tripArray = array_merge(['id' => $this->id], $tripArray);
        return $tripArray;
    }

    protected $appends = ['rating_customer',
                        'rating_captain',
                        'service_name',
                        'captain_profile',
                        'customer_profile'
                    ];

    protected $hidden = ['rating','updated_at'];

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y h:i A');
    }

    public function getServiceNameAttribute()
    {
        if ($this->service) {
            return $this->service->name;
        }

        return null;
    }

    public function getCaptainProfileAttribute()
    {
        if ($this->captain) {
            return $this->captain->documents()->where('name' , 'Profile')->first()->path;
        }

        return "captainDocument/default/default.png";
    }


    public function getCustomerProfileAttribute()
    {
        if ($this->customer && $this->customer->customerDetail) {
            return $this->customer->customerDetail->profile_picture;
        }

        return "customer/default/default.png";
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'trip_id');
    }


    public function service()
    {
        return $this->belongsTo(CaptainService::class);
    }


    public function getRatingCustomerAttribute()
    {
        if ($this->rating() && $this->customer_id) {
            return $this->rating()->where([
                'user_id' => $this->customer_id,
                'trip_id' => $this->id
                ])->value('rating');
        }
        return null;
    }


    public function getRatingCaptainAttribute()
    {
        if ($this->rating() && $this->captain_id) {
            return $this->rating()->where([
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


    public function offers()
    {
        return $this->hasMany(Offer::class, 'trip_id');
    }

}

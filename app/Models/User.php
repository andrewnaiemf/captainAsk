<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject{
	use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'id',
		'f_name',
		'l_name',
		'email',
        'phone',
        'verified',
        'status',
        'online',
		'password',
		'account_type', // admin | user
		'admin_group_id', // admin_group_id
		'created_at',
		'updated_at',
		'deleted_at',
	];

    protected $visible = ['id', 'uuid', 'f_name', 'l_name', 'verified', 'online' ,'phone' ,'customer_profile'];


	protected $deleted_at = 'deleted_at';

    //to arrange the user aattribute and push the id to the beginning of the array
    public function toArray()
    {
        $userArray = parent::toArray();
        $userArray = array_merge(['id' => $this->id], $userArray);
        return $userArray;
    }
    protected $appends = ['fullname' ,'customer_profile', 'uuid'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

	public function getCreatedAtAttribute($date) {
		return empty($date) ? $date : date('Y-m-d', strtotime($date));
	}

	public function getUpdatedAtAttribute($date) {
		return empty($date) ? $date : date('Y-m-d', strtotime($date));
	}

	public function getDeletedAtAttribute($date) {
		return empty($date) ? null : date('Y-m-d', strtotime($date));
	}

	public function getEmailVerifiedAtAttribute($date) {
		return empty($date) ? null : date('Y-m-d', strtotime($date));
	}

    public function getFullNameAttribute()
    {
        return $this->f_name . ' ' . $this->l_name;
    }

    public function getCustomerProfileAttribute()
    {
        if ($this->customerDetail) {
            return $this->customerDetail->profile_picture;
        }

        return "customer/default/default.png";
    }

    public function getUuidAttribute()
    {
        return ($this->id * 15) % 26;
    }

	public function admingroup() {
		return $this->belongsTo(AdminGroup::class, 'admin_group_id');
	}


    public function chats()
    {
        return $this->hasOne(Chat::class, 'user_id');

    }

    public function trips()
    {
        return $this->hasMany(Trip::class, 'customer_id');

    }

    public function customerDetail(){
        return $this->hasOne(CustomerDetail::class, 'user_id');
    }
}

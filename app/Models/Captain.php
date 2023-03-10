<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Captain extends Authenticatable  implements JWTSubject{
	use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = "users";
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

    protected $appends = ['fullname'];

      //to arrange the user aattribute and push the id to the beginning of the array
      public function toArray()
      {
          $userArray = parent::toArray();
          $userArray = array_merge(['id' => $this->id], $userArray);
          return $userArray;
      }

    protected $visible = ['id', 'f_name', 'l_name', 'status', 'verified', 'online' ,'phone','captainDetail','documents','captainService'];

	protected $deleted_at = 'deleted_at';

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

	public function admingroup() {
		return $this->belongsTo(AdminGroup::class, 'admin_group_id');
	}

    public function documents(){
        return $this->hasMany(CaptainDocument::class);
    }

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

    public function getFullNameAttribute()
    {
        return $this->f_name . ' ' . $this->l_name;
    }

    public function getStatusAttribute()
    {

         if($this->attributes['status'] == 'Accepted'){
             return 'Accepted' ;
         }

        $documents = $this->documents()->pluck('status','type');

        if(count($documents) == 0){
            return 'New' ;
        }

        // Initialize a counter for each status value
        $acceptedCount = 0;
        $rejectedCount = 0;


        // Loop through the related rows and count the occurrences of each status value
        foreach ($documents as $document) {
            switch ($document) {
                case 'Accepted' :
                    $acceptedCount++ ;
                break;

                case 'Rejected' :
                    $rejectedCount++ ;
                break;
            }
        }
        $status = 'Pending' ;


        if (   $acceptedCount == 6 && $rejectedCount == 0 ) {
            $status = 'Accepted' ;
        }
        elseif ( $rejectedCount == 6 )
        {
            $status = 'Rejected' ;

        }elseif($rejectedCount < 6)
        {
            $status = 'Pending' ;

        }
        $this->attributes['status'] =  $status ;

        $this->save() ;

        return $status ;
    }


    public function captainDetail(){
        return $this->hasOne(CaptainDetails::class, 'user_id');
    }

    public function cards()
    {
        return $this->hasMany(CaptainCard::class);

    }
}

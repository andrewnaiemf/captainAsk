<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainDetails extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = [
        'car_license_front',
        'car_license_back',
        'captain_license',
        'car_form',
        'insurance_documentation'
    ];

	protected $deleted_at = 'deleted_at';


    public function getCreatedAtAttribute($date) {
		return empty($date) ? $date : date('Y-m-d', strtotime($date));
	}

	public function getUpdatedAtAttribute($date) {
		return empty($date) ? $date : date('Y-m-d', strtotime($date));
	}

	public function getDeletedAtAttribute($date) {
		return empty($date) ? null : date('Y-m-d', strtotime($date));
	}


}

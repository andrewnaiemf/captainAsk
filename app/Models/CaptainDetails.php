<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainDetails extends Model
{
    use HasFactory;

    protected $fillable = ['service_id','earn_area','user_id'];

    public $table = 'captain_details';

    public function user() {
		return $this->belongsTo(User::class, 'id');
	}
}

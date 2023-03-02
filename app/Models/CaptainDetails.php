<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainDetails extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'earn_area', 'user_id'];

    protected $table = 'captain_details';

    protected $visible = ['id', 'service_id', 'earn_area', 'user_id'];


    public function user() {
		return $this->belongsTo(Captain::class, 'id');
	}
}

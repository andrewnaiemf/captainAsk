<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainCarDetail extends Model
{
    use HasFactory;

    public $fillable = ['id','color','model','arabic_number','arabic_letters','english_number','english_letters','captain_id'];

    protected $hidden = [
		'created_at',
		'updated_at',
	];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainService extends Model
{
    use HasFactory;


    protected $table = "captain_service";

    public $fillable = [ 'name' ,'image' ];

    public $visable = [ 'name' ,'image' ];

    protected $hidden = ['created_at','updated_at'];



}

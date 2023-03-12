<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainDocument extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = ['id','name','path','type','status','captain_id'];

    public $visible = ['id','name','path','type','status','captain'];


    public function toArray()
    {
        $array = parent::toArray();

        if(request()->is('api/*')) {
            unset($array['name']);
            unset($array['captain']);
        }

        return $array;
    }


    public function captain(){
        return $this->belongsTo(Captain::class);
    }


}

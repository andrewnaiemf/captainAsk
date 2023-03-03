<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainDocument extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = ['id','name','path','type','status'];

    public function captain(){
        return $this->belongsTo(Captain::class);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'chat';

    protected $fillable = ['user_id', 'captain_id'];

    protected $visible = ['captain', 'user'];

    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

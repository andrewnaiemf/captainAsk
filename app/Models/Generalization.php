<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generalization extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable=['content'];
}

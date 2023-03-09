<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'card_id', 'user_id'];

    protected $visible = ['id', 'amount', 'card', 'captain'];


    protected $table = 'withdraw';

    public function card()
    {
        return $this->belongsTo(CaptainCard::class);
    }

    public function captain()
    {
        return $this->belongsTo(Captain::class);
    }

}

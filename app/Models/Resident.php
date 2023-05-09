<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;
    protected $guarded = [];
    //belong to card
    public function Card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }
}

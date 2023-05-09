<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    //has one
    public function Resident()
    {
        return $this->hasOne(Resident::class, 'card_id');
    }

}

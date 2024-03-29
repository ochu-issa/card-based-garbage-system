<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $guarded = [];

    //has one
    // public function Resident()
    // {
    //     return $this->hasOne(Resident::class, 'card_id');
    // }

    public function resident()
    {
        return $this->hasOne(Resident::class)->withDefault();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($card) {
            $card->resident()->delete();
        });
    }

    public function requestService()
    {
        return $this->hasMany(RequestService::class);
    }



}

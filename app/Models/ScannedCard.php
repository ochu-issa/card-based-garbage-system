<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScannedCard extends Model
{
    use HasFactory;
    protected $guarded = [];
    //belong to card
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "address",
        "coordinate_lat",
        "coordinate_long",
        "views",
        "area",
        "images",
    ];
}

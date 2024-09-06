<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourCity extends Model
{
    use HasFactory;
    protected $table = 'tour_cities';
    protected $fillable = ['tour_id', 'city_id', 'stop_order'];
}

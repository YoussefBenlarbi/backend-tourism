<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'title', 'description', 'image_url'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_destinations')->withPivot('stop_order');
    }
}

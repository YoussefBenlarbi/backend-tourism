<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'destination_id', 'image_url'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_cities');
    }
}

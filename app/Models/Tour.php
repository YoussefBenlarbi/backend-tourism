<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'destination_id', 'category_id', 'name', 'title', 'description',
        'duration', 'price', 'image_url', 'status', 'start_date', 'end_date',
        'max_travelers', 'difficulty_level'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'tour_cities')->withPivot('stop_order');
    }

    public function enquiries()
    {
        return $this->hasMany(EnquiryData::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

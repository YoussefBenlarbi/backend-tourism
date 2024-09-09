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
        'max_travelers', 'difficulty_level',
        'main_image_url'
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

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'tour_destinations')
                    ->withPivot('stop_order')
                    ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(TourImage::class);
    }
}

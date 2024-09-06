<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryData extends Model
{
    use HasFactory;
    protected $fillable = [
        'tour_id', 'name', 'email', 'arrival_date', 'departure_date',
        'number_of_travelers', 'message', 'status'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourDestination extends Model
{
    protected $fillable = ['tour_id', 'destination_id', 'stop_order'];

    // Disable auto-incrementing as we're using a composite primary key
    public $incrementing = false;

    // Define the primary key as an array of the composite key fields
    protected $primaryKey = ['tour_id', 'destination_id'];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('tour_id', '=', $this->getAttribute('tour_id'))
            ->where('destination_id', '=', $this->getAttribute('destination_id'));

        return $query;
    }
    // Define relationships if needed
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
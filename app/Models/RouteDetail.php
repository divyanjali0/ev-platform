<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteDetail extends Model
{
    protected $fillable = [
        'itinerary_customer_revision_id',
        'route_days',
        'hotels',
        'pricing',
    ];

    protected $casts = [
        'route_days' => 'array',
        'hotels'     => 'array',
        'pricing'    => 'array',
    ];

    public function revision()
    {
        return $this->belongsTo(ItineraryCustomerRevision::class, 'itinerary_customer_revision_id');
    }
}

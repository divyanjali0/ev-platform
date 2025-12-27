<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryCustomer extends Model
{
    use HasFactory;

    protected $table = 'itinerary_customer';

    protected $fillable = [
        'vehicle_id',
        'reference_no',
        'theme_ids',
        'city_ids',
        'start_date',
        'end_date',
        'nights',
        'adults',
        'children_6_11',
        'children_above_11',
        'infants',
        'hotel_rating',
        'meal_plan',
        'allergy_issues',
        'allergy_reason',
        'title',
        'full_name',
        'email',
        'whatsapp_code',
        'whatsapp',
        'country',
        'nationality',
        'flight_number',
        'remarks',
        'pickup_location',
        'dropoff_location',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function revisions()
    {
        return $this->hasMany(ItineraryCustomerRevision::class, 'itinerary_customer_id');
    }

    public function latestRevision()
    {
        return $this->hasOne(ItineraryCustomerRevision::class)->latestOfMany();
    }
}

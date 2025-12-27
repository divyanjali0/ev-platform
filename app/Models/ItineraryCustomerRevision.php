<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryCustomerRevision extends Model
{
    use HasFactory;

    protected $table = 'itinerary_customer_revisions';

    protected $fillable = [
        'itinerary_customer_id',
        'revision_no',
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
        'theme_ids' => 'array',
        'city_ids' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relationship: Revision belongs to itinerary customer
     */
    public function itineraryCustomer()
    {
        return $this->belongsTo(ItineraryCustomer::class, 'itinerary_customer_id');
    }

//     use App\Models\TourTheme;
// use App\Models\City;

    public function themes()
    {
        return TourTheme::whereIn('id', $this->theme_ids ?? [])->get();
    }

    public function cities()
    {
        return City::whereIn('id', $this->city_ids ?? [])->get();
    }

}

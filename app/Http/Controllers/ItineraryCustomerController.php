<?php

namespace App\Http\Controllers;

use App\Models\ItineraryCustomer;
use Illuminate\Http\Request;
use App\Models\ItineraryCustomerRevision;

class ItineraryCustomerController extends Controller
{
    public function index()
    {
        $customers = ItineraryCustomer::with([
            'latestRevision',
            'revisions:id,itinerary_customer_id'
        ])
        ->latest()
        ->paginate(10);

        return view('itinerary_customers.index', compact('customers'));
    }

    public function show(ItineraryCustomer $itineraryCustomer)
    {
        return view('itinerary_customers.show', compact('itineraryCustomer'));
    }

    public function edit(ItineraryCustomer $itineraryCustomer)
    {
        return view('itinerary_customers.edit', compact('itineraryCustomer'));
    }

    public function updateRevision(Request $request, ItineraryCustomer $itineraryCustomer)
    {
        $validated = $request->validate([
            'reference_no' => 'required|string|max:100',
            'full_name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'nights' => 'nullable|integer',
            'adults' => 'nullable|integer',
            'children_6_11' => 'nullable|integer',
            'children_above_11' => 'nullable|integer',
            'infants' => 'nullable|integer',
            'hotel_rating' => 'nullable|integer',
            'meal_plan' => 'nullable|string|max:50',
            'allergy_issues' => 'nullable|in:Yes,No',
            'allergy_reason' => 'nullable|string',
            'title' => 'nullable|string|max:20',
            'whatsapp_code' => 'nullable|string|max:10',
            'whatsapp' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'flight_number' => 'nullable|string|max:50',
            'pickup_location' => 'nullable|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'theme_ids' => 'nullable|array',
            'city_ids' => 'nullable|array',
        ]);

        // 1️⃣ Generate next revision number
        $lastRevision = ItineraryCustomerRevision::where('itinerary_customer_id', $itineraryCustomer->id)
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;

        if ($lastRevision && str_contains($lastRevision->revision_no, '-')) {
            $nextNumber = ((int) trim(last(explode('-', $lastRevision->revision_no)))) + 1;
        }

        $revisionNo = $request->reference_no . ' - ' . $nextNumber;

        // 2️⃣ Detect only changed fields
        $changes = [];

        foreach ($validated as $field => $value) {
            $originalValue = $itineraryCustomer->{$field} ?? null;

            // Normalize arrays for comparison
            if (is_array($value)) {
                if (json_encode($originalValue) !== json_encode($value)) {
                    $changes[$field] = $value;
                }
            } else {
                if ($originalValue != $value) {
                    $changes[$field] = $value;
                }
            }
        }

        // 3️⃣ If nothing changed, do not create revision
        if (empty($changes)) {
            return redirect()
                ->route('itinerary-customers.index')
                ->with('info', 'No changes detected. Revision not created.');
        }

        // 4️⃣ Insert only changed fields
        ItineraryCustomerRevision::create(array_merge([
            'itinerary_customer_id' => $itineraryCustomer->id,
            'revision_no' => $revisionNo,
            'reference_no' => $request->reference_no,
        ], $changes));

        return redirect()
            ->route('itinerary-customers.index')
            ->with('success', "Revision {$revisionNo} saved with updated fields only.");
    }


    public function destroy(ItineraryCustomer $itineraryCustomer)
    {
        $itineraryCustomer->delete();

        return redirect()
            ->route('itinerary-customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}

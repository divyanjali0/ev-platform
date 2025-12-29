<?php

namespace App\Http\Controllers;

use App\Models\ItineraryCustomer;
use Illuminate\Http\Request;
use App\Models\ItineraryCustomerRevision;
use App\Models\TourTheme;
use App\Models\City;
use App\Models\RouteDetail;
use Illuminate\Support\Facades\Storage;

class ItineraryCustomerController extends Controller
{

    function normalizeArray($value)
    {
        if (empty($value)) {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }

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
        $themes = TourTheme::select('id', 'theme_name')->orderBy('theme_name')->get();
        $cities = City::select('id', 'name')->orderBy('name')->get();

        return view('itinerary_customers.edit', compact(
            'itineraryCustomer',
            'themes',
            'cities'
        ));
    }

    public function updateRevision(Request $request, ItineraryCustomer $itineraryCustomer)
    {
        $validated = $request->validate([
            'reference_no' => 'required|string|max:100',

            'full_name' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150',
            'whatsapp_code' => 'nullable|string|max:10',
            'whatsapp' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',

            'nights' => 'nullable|integer',
            'adults' => 'nullable|integer',
            'children_6_11' => 'nullable|integer',
            'children_above_11' => 'nullable|integer',
            'infants' => 'nullable|integer',

            'hotel_rating' => 'nullable|string|max:50',
            'meal_plan' => 'nullable|string|max:50',
            'allergy_reason' => 'nullable|string',

            'pickup_location' => 'nullable|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'flight_number' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',

            'theme_ids' => 'nullable|array',
            'city_ids'  => 'nullable|array',

            'day_date' => 'nullable|array',
            'day_description' => 'nullable|array',
            'day_images' => 'nullable|array',

            'hotels' => 'nullable|array',
            'pricing' => 'nullable|array',
        ]);

        /* =====================================================
        1️⃣ DETECT BASIC DETAIL CHANGES (STRICT)
        ===================================================== */
        $numericFields = [
            'nights','adults','children_6_11','children_above_11','infants'
        ];

        $stringFields = [
            'full_name','email','whatsapp_code','whatsapp','country',
            'hotel_rating','meal_plan','allergy_reason',
            'pickup_location','dropoff_location','flight_number','remarks'
        ];

        $changes = [];

        /* ---------------- NUMERIC FIELDS ---------------- */
        foreach ($numericFields as $field) {
            if (!$request->has($field)) {
                continue;
            }

            $incoming = $request->input($field);

            // Ignore empty submissions
            if ($incoming === '' || $incoming === null) {
                continue;
            }

            $incoming = (int) $incoming;
            $existing = (int) ($itineraryCustomer->{$field} ?? 0);

            if ($incoming !== $existing) {
                $changes[$field] = $incoming;
            }
        }

        /* ---------------- STRING FIELDS ---------------- */
        foreach ($stringFields as $field) {
            if (!$request->has($field)) {
                continue;
            }

            $incoming = trim((string) $request->input($field));
            $existing = trim((string) ($itineraryCustomer->{$field} ?? ''));

            if ($incoming === '' && $existing === '') {
                continue;
            }

            if ($incoming !== $existing) {
                $changes[$field] = $incoming;
            }
        }

        /* ---------------- DATES (ONLY IF REALLY CHANGED) ---------------- */
        foreach (['start_date', 'end_date'] as $dateField) {
            if (!empty($validated[$dateField])) {
                $incoming = \Carbon\Carbon::parse($validated[$dateField])->format('Y-m-d');
                $existing = optional($itineraryCustomer->{$dateField})
                    ? \Carbon\Carbon::parse($itineraryCustomer->{$dateField})->format('Y-m-d')
                    : null;

                if ($incoming !== $existing) {
                    $changes[$dateField] = $validated[$dateField];
                }
            }
        }

        /* ---------------- THEME IDS ---------------- */
        $incomingThemes = $validated['theme_ids'] ?? [];
        $existingThemes = $itineraryCustomer->theme_ids ?? [];

        sort($incomingThemes);
        sort($existingThemes);

        if ($incomingThemes !== $existingThemes) {
            $changes['theme_ids'] = $incomingThemes;
        }

        /* ---------------- CITY IDS ---------------- */
        $incomingCities = $validated['city_ids'] ?? [];
        $existingCities = $itineraryCustomer->city_ids ?? [];

        sort($incomingCities);
        sort($existingCities);

        if ($incomingCities !== $existingCities) {
            $changes['city_ids'] = $incomingCities;
        }

        /* =====================================================
        2️⃣ CREATE REVISION ONLY IF CHANGES EXIST
        ===================================================== */
        $revision = null;

        if (!empty($changes)) {

            $lastRevision = ItineraryCustomerRevision::where('itinerary_customer_id', $itineraryCustomer->id)
                ->latest()
                ->first();

            $next = 1;
            if ($lastRevision && str_contains($lastRevision->revision_no, '-')) {
                $next = (int) last(explode('-', $lastRevision->revision_no)) + 1;
            }

            $revision = ItineraryCustomerRevision::create(array_merge([
                'itinerary_customer_id' => $itineraryCustomer->id,
                'reference_no' => $request->reference_no,
                'revision_no' => $request->reference_no . ' - ' . $next,
            ], $changes));
        }

        /* =====================================================
        3️⃣ BUILD ROUTE DAYS (SKIP EMPTY)
        ===================================================== */
        $routeDays = [];

        if ($request->day_date) {
            foreach ($request->day_date as $cityId => $date) {

                $description = $request->day_description[$cityId] ?? null;
                $plainText = trim(strip_tags($description));
                $hasImages = $request->hasFile("day_images.$cityId");

                if (!$date && $plainText === '' && !$hasImages) {
                    continue;
                }

                $images = [];
                if ($hasImages) {
                    foreach ($request->file("day_images.$cityId") as $file) {
                        $images[] = $file->store('route-images', 'public');
                    }
                }

                $routeDays[] = [
                    'city_id' => (int) $cityId,
                    'city_name' => City::find($cityId)?->name,
                    'date' => $date,
                    'description' => $description,
                    'images' => $images,
                ];
            }
        }

        $hotels  = $request->hotels ?? null;
        $pricing = $request->pricing ?? null;

        $hasRouteData = !empty($routeDays) || !empty($hotels) || !empty($pricing);

        /* =====================================================
        4️⃣ ROUTE DETAILS — ONLY IF CHANGED
        ===================================================== */
        if ($hasRouteData) {

            if (!$revision) {
                $revision = ItineraryCustomerRevision::where('itinerary_customer_id', $itineraryCustomer->id)
                    ->latest()
                    ->first();
            }

            if ($revision) {
                $existingRoute = RouteDetail::where('itinerary_customer_revision_id', $revision->id)->first();

                $changed =
                    !$existingRoute ||
                    json_encode($existingRoute->route_days) !== json_encode($routeDays) ||
                    json_encode($existingRoute->hotels) !== json_encode($hotels) ||
                    json_encode($existingRoute->pricing) !== json_encode($pricing);

                if ($changed) {
                    RouteDetail::updateOrCreate(
                        ['itinerary_customer_revision_id' => $revision->id],
                        [
                            'route_days' => $routeDays ?: null,
                            'hotels' => $hotels ?: null,
                            'pricing' => $pricing ?: null,
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('itinerary-customers.index')
            ->with('success', 'Data saved successfully.');
    }
}

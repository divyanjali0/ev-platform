<x-app-layout>
    <style>
        label {
            font-weight: 500;
        }
        .selected-city {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>

@php
    $selectedTheme = $itineraryCustomer->theme_ids ? json_decode($itineraryCustomer->theme_ids)[0] ?? null : null;
    $selectedCities = collect(json_decode($itineraryCustomer->city_ids ?? '[]'));
@endphp

<main class="ml-64 pt-14 p-2">
    <div class="container">

        <div class="card shadow mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="fw-bold">Edit Tour Request – {{ $itineraryCustomer->reference_no }}</h5>
                <a href="{{ route('itinerary-customers.index') }}" class="btn btn-sm btn-secondary">Back</a>
            </div>

            <div class="card-body">
                <!-- SINGLE FORM FOR EVERYTHING -->
                <form action="{{ route('itinerary-customers.updateRevision', $itineraryCustomer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="itinerary_customer_id" value="{{ $itineraryCustomer->id }}">
                    <input type="hidden" name="reference_no" value="{{ $itineraryCustomer->reference_no }}">

                    <div class="row g-3">
                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="{{ $itineraryCustomer->full_name }}">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $itineraryCustomer->email }}">
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <div class="input-group">
                                <input type="text" name="whatsapp_code" class="form-control" placeholder="Code" value="{{ $itineraryCustomer->whatsapp_code }}">
                                <input type="text" name="whatsapp" class="form-control" placeholder="Number" value="{{ $itineraryCustomer->whatsapp }}">
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ $itineraryCustomer->country }}">
                        </div>

                        <!-- Dates -->
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ \Carbon\Carbon::parse($itineraryCustomer->start_date)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ \Carbon\Carbon::parse($itineraryCustomer->end_date)->format('Y-m-d') }}">
                        </div>

                        <!-- Adults / Children / Infants -->
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Adults</label>
                                    <input type="number" name="adults" class="form-control" value="{{ $itineraryCustomer->adults }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Children (6-11)</label>
                                    <input type="number" name="children_6_11" class="form-control" value="{{ $itineraryCustomer->children_6_11 }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Children (12+)</label>
                                    <input type="number" name="children_above_11" class="form-control" value="{{ $itineraryCustomer->children_above_11 }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Infants</label>
                                    <input type="number" name="infants" class="form-control" value="{{ $itineraryCustomer->infants }}">
                                </div>
                            </div>
                        </div>

                        <!-- Nights, Hotel & Meal -->
                        <div class="col-md-6">
                            <label class="form-label">Nights</label>
                            <input type="number" name="nights" class="form-control" value="{{ $itineraryCustomer->nights }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hotel Rating</label>
                            <input type="text" name="hotel_rating" class="form-control" value="{{ $itineraryCustomer->hotel_rating }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meal Plan</label>
                            <input type="text" name="meal_plan" class="form-control" value="{{ $itineraryCustomer->meal_plan }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Allergy Issues</label>
                            <input type="text" name="allergy_reason" class="form-control" value="{{ $itineraryCustomer->allergy_reason }}">
                        </div>

                            <!-- Pickup / Dropoff / Flight -->
                            <div class="col-md-6">
                                <label class="form-label">Pickup Location</label>
                                <input type="text" name="pickup_location" class="form-control" value="{{ $itineraryCustomer->pickup_location }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dropoff Location</label>
                                <input type="text" name="dropoff_location" class="form-control" value="{{ $itineraryCustomer->dropoff_location }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Flight Number</label>
                                <input type="text" name="flight_number" class="form-control" value="{{ $itineraryCustomer->flight_number }}">
                            </div>

                        <!-- Remarks -->
                        <div class="col-12">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3">{{ $itineraryCustomer->remarks }}</textarea>
                        </div>

                        <!-- ================= TOUR PREFERENCES ================= -->
                        <!-- Themes (Radio Buttons) -->
                        <div class="col-md-12 mt-4 mb-3">
                            <label class="form-label">Tour Themes</label>
                            <div class="row">
                                @foreach($themes as $theme)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="theme_ids[]" value="{{ $theme->id }}" id="theme{{ $theme->id }}" {{ $selectedTheme == $theme->id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="theme{{ $theme->id }}">{{ $theme->theme_name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Cities -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Cities</label>

                            <!-- Selected Cities -->
                            <div id="selectedCities" class="mb-2">
                                @foreach($selectedCities as $cityId)
                                    @php $city = $cities->firstWhere('id', $cityId); @endphp
                                    @if($city)
                                        <span class="badge bg-primary selected-city" data-id="{{ $city->id }}">
                                            {{ $city->name }}
                                            <button type="button" class="btn-close btn-close-white btn-sm remove-city" aria-label="Remove"></button>
                                            <input type="hidden" name="city_ids[]" value="{{ $city->id }}">
                                        </span>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Dropdown to add new cities -->
                            <select id="cityDropdown" class="form-select">
                                <option value="">-- Select City to Add --</option>
                                @foreach($cities as $city)
                                    @if(!$selectedCities->contains($city->id))
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <a href="{{ route('itinerary-customers.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Save All</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dd = document.getElementById('cityDropdown'), sel = document.getElementById('selectedCities');
            dd.addEventListener('change', () => {
                if (!dd.value || sel.querySelector(`[data-id='${dd.value}']`)) return;
                sel.insertAdjacentHTML('beforeend', `<span class="badge bg-primary selected-city" data-id="${dd.value}">${dd.options[dd.selectedIndex].text} <button type="button" class="btn-close btn-close-white btn-sm remove-city" aria-label="Remove"></button><input type="hidden" name="city_ids[]" value="${dd.value}"></span>`);
                dd.value = '';
            });
            sel.addEventListener('click', e => e.target.classList.contains('remove-city') && e.target.parentElement.remove());
        });
    </script>

</x-app-layout>

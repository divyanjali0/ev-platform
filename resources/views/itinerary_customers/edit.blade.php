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
                        <div class="card shadow mb-3">
                            <div class="card-header fw-bold">Tour Preferences</div>
                                <div class="card-body">
                                    <form id="tourForm" action="{{ route('itinerary-customers.updateRevision', $itineraryCustomer) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Themes (Radio Buttons) -->
                                        <div class="mb-3">
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
                                        <div class="mb-3">
                                            <label class="form-label">Cities</label>
                                            <select id="cityDropdown" class="form-select">
                                                <option value="">-- Select City to Add --</option>
                                                @foreach($cities as $city)
                                                    @if(!$selectedCities->contains($city->id))
                                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Selected Cities / Itinerary Days -->
                                        <div id="itineraryDaysContainer">
                                            @foreach($selectedCities as $cityId)
                                                @php $city = $cities->firstWhere('id', $cityId); @endphp
                                                @if($city)
                                                    <div class="day-card" data-city-id="{{ $city->id }}">
                                                        <h5>City: {{ $city->name }} <button type="button" class="btn-close remove-day float-end" aria-label="Remove"></button></h5>
                                                        <div class="mb-2">
                                                            <label>Date</label>
                                                            <input type="date" name="day_date[{{ $city->id }}]" class="form-control">
                                                        </div>
                                                        <div>
                                                            <label>Description</label>
                                                            <div class="quill-editor"></div>
                                                            <input type="hidden" name="day_description[{{ $city->id }}]">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <div class="mt-4 d-flex gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-success">Save Tour Preferences & Itinerary</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Quill JS -->
                        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const cityDropdown = document.getElementById('cityDropdown');
                                const itineraryContainer = document.getElementById('itineraryDaysContainer');
                                const quillEditors = {};

                                function addCityDay(cityId, cityName) {
                                    if (itineraryContainer.querySelector(`[data-city-id='${cityId}']`)) return;

                                    const dayCard = document.createElement('div');
                                    dayCard.classList.add('day-card');
                                    dayCard.setAttribute('data-city-id', cityId);

                                    dayCard.innerHTML = `
                                        <h5>City: ${cityName} <button type="button" class="btn-close remove-day float-end" aria-label="Remove"></button></h5>
                                        <div class="mb-2">
                                            <label>Date</label>
                                            <input type="date" name="day_date[${cityId}]" class="form-control">
                                        </div>
                                        <div>
                                            <label>Description</label>
                                            <div class="quill-editor" id="editor-${cityId}"></div>
                                            <input type="hidden" name="day_description[${cityId}]">
                                        </div>
                                    `;
                                    itineraryContainer.appendChild(dayCard);

                                    // Initialize Quill editor
                                    quillEditors[cityId] = new Quill(`#editor-${cityId}`, { theme: 'snow' });
                                }

                                // Initialize Quill for existing days
                                document.querySelectorAll('.quill-editor').forEach((editorDiv) => {
                                    const cityId = editorDiv.parentElement.parentElement.dataset.cityId;
                                    quillEditors[cityId] = new Quill(editorDiv, { theme: 'snow' });
                                });

                                // Add city from dropdown
                                cityDropdown.addEventListener('change', function () {
                                    const cityId = this.value;
                                    if (!cityId) return;
                                    const cityName = this.options[this.selectedIndex].text;
                                    addCityDay(cityId, cityName);
                                    this.value = '';
                                });

                                // Remove day
                                itineraryContainer.addEventListener('click', function(e) {
                                    if(e.target.classList.contains('remove-day')) {
                                        e.target.closest('.day-card').remove();
                                    }
                                });

                                // On form submit, copy Quill content to hidden inputs
                                document.getElementById('tourForm').addEventListener('submit', function () {
                                    for (const cityId in quillEditors) {
                                        const editor = quillEditors[cityId];
                                        const hiddenInput = document.querySelector(`input[name='day_description[${cityId}]']`);
                                        if(hiddenInput) {
                                            hiddenInput.value = editor.root.innerHTML;
                                        }
                                    }
                                });
                            });
                        </script>
                        
                    </div>

                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <a href="{{ route('itinerary-customers.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Save All</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

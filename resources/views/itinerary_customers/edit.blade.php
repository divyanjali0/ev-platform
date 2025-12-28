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

        .quill-editor {
            min-height: 180px;
            background: #fff;
        }

        .ql-container {
            min-height: 180px;
        }

        .ql-editor {
            min-height: 160px;
            line-height: 1.6;
            font-size: 14px;
        }

        .accordion-button {
            border: 2px solid #000000d9;
        font-weight: 700;
        border-radius: 0 !important;
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
                <form id="tourForm" action="{{ route('itinerary-customers.updateRevision', $itineraryCustomer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="itinerary_customer_id" value="{{ $itineraryCustomer->id }}">
                    <input type="hidden" name="reference_no" value="{{ $itineraryCustomer->reference_no }}">

                    <div class="accordion" id="tourAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#basicDetails" aria-expanded="true">
                                    Customer & Trip Details
                                </button>
                            </h2>

                            <div id="basicDetails" class="accordion-collapse collapse show" data-bs-parent="#tourAccordion">
                                <div class="accordion-body">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================= TOUR PREFERENCES ================= -->
                        <div class="accordion-item mt-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#tourPreferences">
                                    Tour Preferences & Itinerary
                                </button>
                            </h2>

                            <div id="tourPreferences" class="accordion-collapse collapse" data-bs-parent="#tourAccordion">
                                <div class="accordion-body">
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
                                    <div id="itineraryDaysContainer" class="mt-4">
                                        @foreach($selectedCities as $cityId)
                                            @php $city = $cities->firstWhere('id', $cityId); @endphp
                                            @if($city)
                                                <div class="day-card my-4 border rounded p-3" data-city-id="{{ $city->id }}">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h5 class="fw-bold mb-0">City: {{ $city->name }}</h5>
                                                        <button type="button" class="btn-close remove-day"></button>
                                                    </div>

                                                    <div class="mb-3 w-25">
                                                        <label class="form-label">Date</label>
                                                        <input type="date"
                                                            name="day_date[{{ $city->id }}]"
                                                            class="form-control">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <div class="quill-editor"
                                                            id="editor-{{ $city->id }}"></div>
                                                        <input type="hidden"
                                                            name="day_description[{{ $city->id }}]">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Images</label>
                                                        <input type="file"
                                                            name="day_images[{{ $city->id }}][]"
                                                            class="form-control"
                                                            multiple
                                                            accept="image/*">
                                                        <small class="text-muted">
                                                            You can upload multiple images (jpg, png, webp)
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2 justify-content-end">
                            <a href="{{ route('itinerary-customers.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                Save All
                            </button>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const cityDropdown = document.getElementById('cityDropdown');
                                const itineraryContainer = document.getElementById('itineraryDaysContainer');
                                const quillEditors = {};

                                function initQuill(cityId) {
                                    if (quillEditors[cityId]) return;

                                    quillEditors[cityId] = new Quill(`#editor-${cityId}`, {
                                        theme: 'snow',
                                        modules: {
                                            toolbar: [
                                                [{ header: [1, 2, 3, false] }],   
                                                ['bold', 'italic', 'underline'],
                                                [{ list: 'ordered' }, { list: 'bullet' }],
                                                ['link'],
                                                ['clean']
                                            ]
                                        }
                                    });
                                }

                                function addCityDay(cityId, cityName) {
                                    if (itineraryContainer.querySelector(`[data-city-id="${cityId}"]`)) return;

                                    const wrapper = document.createElement('div');
                                    wrapper.className = 'day-card my-4 border rounded p-3';
                                    wrapper.dataset.cityId = cityId;

                                    wrapper.innerHTML = `
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="fw-bold mb-0">City: ${cityName}</h5>
                                            <button type="button" class="btn-close remove-day"></button>
                                        </div>

                                        <div class="mb-3 w-25">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="day_date[${cityId}]" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <div class="quill-editor" id="editor-${cityId}"></div>
                                            <input type="hidden" name="day_description[${cityId}]">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Images</label>
                                            <input type="file" name="day_images[${cityId}][]" class="form-control" multiple accept="image/*">
                                            <small class="text-muted">
                                                You can upload multiple images (jpg, png, webp)
                                            </small>
                                        </div>
                                    `;

                                    itineraryContainer.appendChild(wrapper);
                                    initQuill(cityId);
                                }

                                /* Init existing editors */
                                document.querySelectorAll('.day-card').forEach(card => {
                                    initQuill(card.dataset.cityId);
                                });

                                /* Add city */
                                cityDropdown.addEventListener('change', function () {
                                    if (!this.value) return;
                                    addCityDay(this.value, this.options[this.selectedIndex].text);
                                    this.value = '';
                                });

                                /* Remove city */
                                itineraryContainer.addEventListener('click', function (e) {
                                    if (e.target.classList.contains('remove-day')) {
                                        e.target.closest('.day-card').remove();
                                    }
                                });

                                /* Sync Quill → hidden inputs */
                                document.getElementById('tourForm').addEventListener('submit', function () {
                                    for (const cityId in quillEditors) {
                                        const hidden = document.querySelector(`input[name="day_description[${cityId}]"]`);
                                        if (hidden) {
                                            hidden.value = quillEditors[cityId].root.innerHTML;
                                        }
                                    }
                                });
                            });
                        </script>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
</x-app-layout>

<x-app-layout>
    <style>
        label {
            font-weight: 500;
        }
    </style>

    <main class="ml-64 pt-14 p-2">
        <div class="container">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold">Edit Tour Request - {{ $itineraryCustomer->reference_no }}</h5>
                    <a href="{{ route('itinerary-customers.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('itinerary-customers.updateRevision', $itineraryCustomer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="itinerary_customer_id" value="{{ $itineraryCustomer->id }}">
                        <input type="hidden" name="reference_no" value="{{ $itineraryCustomer->reference_no }}">

                        <div class="accordion" id="customerAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCustomer">
                                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCustomer" aria-expanded="true" aria-controls="collapseCustomer">
                                        Customer Details
                                    </button>
                                </h2>
                                <div id="collapseCustomer" class="accordion-collapse collapse show" aria-labelledby="headingCustomer" data-bs-parent="#customerAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">

                                            <!-- Reference No -->
                                            <div class="col-md-6">
                                                <label class="form-label">Reference No</label>
                                                <input type="text" name="reference_no" class="form-control" value="{{ $itineraryCustomer->reference_no }}" disabled>
                                            </div>

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

                                            <!-- Nationality -->
                                            <div class="col-md-6">
                                                <label class="form-label">Nationality</label>
                                                <input type="text" name="nationality" class="form-control" value="{{ $itineraryCustomer->nationality }}">
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

                                            <!-- Grouped Counts: Adults, Children, Infants -->
                                            <div class="col-md-12">
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

                                            <!-- Nights -->
                                            <div class="col-md-6">
                                                <label class="form-label">Nights</label>
                                                <input type="number" name="nights" class="form-control" value="{{ $itineraryCustomer->nights }}">
                                            </div>

                                            <!-- Hotel & Meal -->
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
                        </div>

                        <div class="mt-4 d-flex gap-2 justify-content-end">
                            <a href="{{ route('itinerary-customers.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Update Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>

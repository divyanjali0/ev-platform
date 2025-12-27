<x-app-layout>
    <main class="ml-64 pt-14 p-6">
        <div class="container">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Customer Details - {{ $itineraryCustomer->full_name }}</h5>
                    <a href="{{ route('itinerary-customers.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th>Reference No</th>
                                <td>{{ $itineraryCustomer->reference_no }}</td>
                            </tr>
                            <tr>
                                <th>Full Name</th>
                                <td>{{ $itineraryCustomer->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $itineraryCustomer->email }}</td>
                            </tr>
                            <tr>
                                <th>WhatsApp</th>
                                <td>{{ $itineraryCustomer->whatsapp_code }} {{ $itineraryCustomer->whatsapp }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ $itineraryCustomer->country }}</td>
                            </tr>
                            <tr>
                                <th>Nationality</th>
                                <td>{{ $itineraryCustomer->nationality }}</td>
                            </tr>
                            <tr>
                                <th>Start Date</th>
                                <td>{{ \Carbon\Carbon::parse($itineraryCustomer->start_date)->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>End Date</th>
                                <td>{{ \Carbon\Carbon::parse($itineraryCustomer->end_date)->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Nights</th>
                                <td>{{ $itineraryCustomer->nights }}</td>
                            </tr>
                            <tr>
                                <th>Adults</th>
                                <td>{{ $itineraryCustomer->adults }}</td>
                            </tr>
                            <tr>
                                <th>Children (6-11)</th>
                                <td>{{ $itineraryCustomer->children_6_11 }}</td>
                            </tr>
                            <tr>
                                <th>Children (12+)</th>
                                <td>{{ $itineraryCustomer->children_above_11 }}</td>
                            </tr>
                            <tr>
                                <th>Infants</th>
                                <td>{{ $itineraryCustomer->infants }}</td>
                            </tr>
                            <tr>
                                <th>Hotel Rating</th>
                                <td>{{ $itineraryCustomer->hotel_rating }}</td>
                            </tr>
                            <tr>
                                <th>Meal Plan</th>
                                <td>{{ $itineraryCustomer->meal_plan }}</td>
                            </tr>
                            <tr>
                                <th>Allergy Issues</th>
                                <td>{{ $itineraryCustomer->allergy_issues ? $itineraryCustomer->allergy_reason : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Pickup Location</th>
                                <td>{{ $itineraryCustomer->pickup_location }}</td>
                            </tr>
                            <tr>
                                <th>Dropoff Location</th>
                                <td>{{ $itineraryCustomer->dropoff_location }}</td>
                            </tr>
                            <tr>
                                <th>Flight Number</th>
                                <td>{{ $itineraryCustomer->flight_number }}</td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>{{ $itineraryCustomer->remarks }}</td>
                            </tr>
                            <tr>
                                <th>Booking Added On</th>
                                <td>{{ $itineraryCustomer->created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>

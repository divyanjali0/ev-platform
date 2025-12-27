<x-app-layout>
    <main class="ml-64 pt-14 p-3">
        <div class="d-flex justify-content-between mb-4">
            <h1 class="h5 fw-bold">Tour Requests</h1>
        </div>

        <div class="table-responsive">
            <table id="customers-table" class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Reference No</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
             <tbody>
                @foreach($customers as $customer)

                    @php
                        $data = $customer->latestRevision ?? $customer;
                        $revisionCount = $customer->revisions->count();
                    @endphp

                    <tr>
                        <td>
                            {{ $data->reference_no }}

                            @if($revisionCount > 0)
                                <span class="badge bg-warning text-dark ms-1">
                                    Revised {{ $revisionCount }}x
                                </span>
                            @endif
                        </td>

                        <td>{{ $data->full_name }}</td>

                        <td>
                            {{ $data->start_date
                                ? \Carbon\Carbon::parse($data->start_date)->format('Y-m-d')
                                : '-' }}
                        </td>

                        <td>
                            {{ $data->end_date
                                ? \Carbon\Carbon::parse($data->end_date)->format('Y-m-d')
                                : '-' }}
                        </td>

                        <td>
                            <a href="{{ route('itinerary-customers.show', $customer) }}"
                            class="btn btn-sm btn-primary">
                                View
                            </a>

                            <a href="{{ route('itinerary-customers.edit', $customer) }}"
                            class="btn btn-sm btn-success">
                                Edit
                            </a>
                        </td>
                    </tr>

                @endforeach
            </tbody>
            </table>
        </div>
    </main>
    <script>
        window.onload = function() {
            $('#customers-table').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                columnDefs: [{ orderable: false, targets: 4 }]
            });
        };
    </script>
</x-app-layout>

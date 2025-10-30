@extends('layouts.master')

@section('title', __('Drivers Details'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.drivers.index') }}">{{ __('Drivers') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-6">
                    <div class="card-body pt-12">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mb-4"
                                    src="{{ asset($driver->profile->profile_image ?? 'assets/img/default/user.png') }}"
                                    height="120" width="120" alt="User avatar" />
                                <div class="user-info text-center">
                                    <h5>{{ $driver->name }}</h5>
                                    <span class="badge bg-label-secondary">Driver</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-4 border-bottom mb-4 mt-4">Personal Details</h5>
                        <div class="info-container">
                            <ul class="list-unstyled mb-6">
                                <li class="mb-2">
                                    <span class="h6">Username:</span>
                                    <span>{{ '@' . $driver->username }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="h6">Email:</span>
                                    <span>{{ $driver->email }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="h6">Contact:</span>
                                    <span>{{ $driver->profile->phone_number ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="h6">Date of Birth:</span>
                                    <span>{{ $driver->profile->dob ? \Carbon\Carbon::parse($driver->profile->dob)->format('d M, Y') : 'N/A' }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="h6">Gender:</span>
                                    <span>{{ $driver->profile->gender ?? 'N/A' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 order-0 order-md-1">
                <div class="card mb-6">
                    <h5 class="card-header">Driver Details</h5>
                    <div class="card-body pt-1">

                        {{-- Vehicle Details --}}
                        @if ($driver->driverVehicle)
                            <h6 class="fw-bold mt-3">Vehicle Information</h6>
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr>
                                        <th>Vehicle Type</th>
                                        <td>{{ $driver->driverVehicle->vehicleType->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Vehicle Name</th>
                                        <td>{{ $driver->driverVehicle->vehicle_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Make</th>
                                        <td>{{ $driver->driverVehicle->vehicle_make ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Model</th>
                                        <td>{{ $driver->driverVehicle->vehicle_model ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Color</th>
                                        <td>{{ $driver->driverVehicle->vehicle_color ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Year</th>
                                        <td>{{ $driver->driverVehicle->vehicle_year ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Plate Number</th>
                                        <td>{{ $driver->driverVehicle->vehicle_plate_number ?? '—' }}</td>
                                    </tr>
                                    @if ($driver->driverVehicle->vehicle_images)
                                        <tr>
                                            <th>Images</th>
                                            <td>
                                                @foreach (json_decode($driver->driverVehicle->vehicle_images, true) as $image)
                                                    <img src="{{ asset('storage/' . $image) }}" alt="Vehicle Image"
                                                        class="rounded border me-2 mb-2" width="100">
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No vehicle details available.</p>
                        @endif

                        <hr>

                        {{-- License Details --}}
                        @if ($driver->driverLicense)
                            <h6 class="fw-bold mt-3">License Information</h6>
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $driver->driverLicense->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>License Number</th>
                                        <td>{{ $driver->driverLicense->license_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $driver->driverLicense->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>License Images</th>
                                        <td>
                                            <img src="{{ asset('storage/' . $driver->driverLicense->front_picture) }}"
                                                width="100" class="me-2 rounded border" alt="Front">
                                            <img src="{{ asset('storage/' . $driver->driverLicense->back_picture) }}"
                                                width="100" class="rounded border" alt="Back">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No license details available.</p>
                        @endif

                        <hr>

                        {{-- CNIC Details --}}
                        @if ($driver->driverCnic)
                            <h6 class="fw-bold mt-3">CNIC Information</h6>
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $driver->driverCnic->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>CNIC Number</th>
                                        <td>{{ $driver->driverCnic->cnic_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Issue Date</th>
                                        <td>{{ \Carbon\Carbon::parse($driver->driverCnic->issue_date)->format('d M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>CNIC Images</th>
                                        <td>
                                            <img src="{{ asset('storage/' . $driver->driverCnic->front_picture) }}"
                                                width="100" class="me-2 rounded border" alt="Front">
                                            <img src="{{ asset('storage/' . $driver->driverCnic->back_picture) }}"
                                                width="100" class="rounded border" alt="Back">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No CNIC details available.</p>
                        @endif

                    </div>
                </div>
            </div>
            <!--/ User Content -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        //
    </script>
@endsection

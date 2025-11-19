@extends('layouts.master')

@section('title', __('Create Promo Code'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.promo-codes.index') }}">{{ __('Promo Codes') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.promo-codes.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row p-5">
                        <h3>{{ __('Add New Promo Code') }}</h3>
                        <div class="mb-4 col-md-4">
                            <label for="name" class="form-label">{{ __('Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                name="name" required placeholder="{{ __('Enter name') }}" autofocus
                                value="{{ old('name') }}" />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="code" class="form-label">{{ __('Code') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('code') is-invalid @enderror" type="text" id="code"
                                name="code" required placeholder="{{ __('Enter code') }}"  value="{{ old('code') }}"/>
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="discount_percentage" class="form-label">{{ __('Discount Percentage (%)') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('discount_percentage') is-invalid @enderror" type="number" max="100" min="0" id="discount_percentage"
                                name="discount_percentage" value="{{ old('discount_percentage') }}" required placeholder="{{ __('Enter discount percentage') }}" />
                            @error('discount_percentage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="valid_from" class="form-label">{{ __('Valid From') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('valid_from') is-invalid @enderror" type="date"
                                id="valid_from" name="valid_from" required value="{{old('valid_from')}}" />
                            @error('valid_from')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="valid_until" class="form-label">{{ __('Valid Untill') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('valid_until') is-invalid @enderror" required type="date"
                                id="valid_until" name="valid_until" value="{{old('valid_until')}}" />
                            @error('valid_until')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="usage_limit_per_user" class="form-label">{{ __('Usage Limit Per User') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('usage_limit_per_user') is-invalid @enderror" type="number" min="0" id="usage_limit_per_user"
                                name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" required placeholder="{{ __('Enter usage limit per user') }}" />
                            @error('usage_limit_per_user')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="usage_limit" class="form-label">{{ __('Usage Limit') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('usage_limit') is-invalid @enderror" type="number" min="0" id="usage_limit"
                                name="usage_limit" value="{{ old('usage_limit') }}" required placeholder="{{ __('Enter usage limit') }}" />
                            @error('usage_limit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Add Promo Code') }}</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection

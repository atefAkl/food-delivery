@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="icofont icofont-ui-home bg-c-yellow card1-icon"></i>{{__('Customer Details')}}</h5>
        <span>This page display all <code>customer</code> details</span>

    </div>
</div>
<div class="row">
    @include('admin.customers.stats')
</div>
<div class="card">
    <div class="card-header">
        <h5><i class="icofont icofont-card"></i>{{__('Basic Info')}}</h5>
        <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li><i class="icofont icofont-simple-left"></i></li>
                <li><i class="icofont icofont-maximize full-card"></i></li>
                <li><i class="icofont icofont-minus minimize-card"></i></li>
                <li><i class="icofont icofont-refresh reload-card"></i></li>
                <li><i class="icofont icofont-error close-card"></i></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <!-- basic Info -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">{{__('Name')}}</label>
                    <input type="text" class="form-control" id="name" value="{{ $data->name }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">{{__('Email')}}</label>
                    <input type="email" class="form-control" id="email" value="{{ $data->email }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">{{__('Phone')}}</label>
                    <input type="text" class="form-control" id="phone" value="{{ $data->phone }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="profile_image">{{__('Profile Image')}}</label>
                    <input type="text" class="form-control" id="profile_image" value="{{ $data->profile_image }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bio">{{__('Bio')}}</label>
                    <input type="text" class="form-control" id="bio" value="{{ $data->bio }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type">{{__('Type')}}</label>
                    <input type="text" class="form-control" id="type" value="{{ $data->type }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
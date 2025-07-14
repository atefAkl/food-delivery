@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{__('Restaurants List')}}</h5>
        <span>This page display all <code>restaurants</code> list</span>
        <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li><i class="icofont icofont-simple-left "></i></li>
                <li><i class="icofont icofont-maximize full-card"></i></li>
                <li><i class="icofont icofont-minus minimize-card"></i></li>
                <li><i class="icofont icofont-refresh reload-card"></i></li>
                <li><i class="icofont icofont-error close-card"></i></li>
            </ul>
        </div>
    </div>
    <div class="card-block table-border-style">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $customer)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->defaultAddress()->city ?? 'No Address' }}</td>
                        <td>
                            <a href="{{ route('admin-restaurants-show', $customer->id) }}" data-toggle="tooltip" data-placement="top" title="Show Restaurant"
                                class="btn py-1 px-2 btn-primary"><i class="ti-eye"></i></a>
                            <a href="{{ route('admin-restaurants-delete', $customer->id) }}" data-toggle="tooltip" data-placement="top" title="Delete Restaurant"
                                class="btn py-1 px-2 btn-danger"><i class="ti-trash"></i></a>
                            <a href="{{ route('admin-restaurants-approve', $customer->id) }}" data-toggle="tooltip" data-placement="top" title="Approve Restaurant"
                                class="btn py-1 px-2 btn-success"><i class="ti-check"></i></a>
                            <a href="{{ route('admin-restaurants-park', $customer->id) }}" data-toggle="tooltip" data-placement="top" title="Park Restaurant"
                                class="btn py-1 px-2 btn-warning"><i class="ti-control-pause"></i></a>
                            <a href="{{ route('admin-restaurants-orders', $customer->id) }}" data-toggle="tooltip" data-placement="top" title="display Restaurant Orders"
                                class="btn py-1 px-2 btn-info"><i class="ti-shopping-cart"></i></a>
                            <a href="{{ route('admin-restaurants-reviews', $customer->id) }}" data-toggle="tooltip" data-placement="top" title="display Restaurant Reviews"
                                class="btn py-1 px-2 btn-secondary"><i class="ti-star"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No customers available for display</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
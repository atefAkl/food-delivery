@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{__('Orders List')}}</h5>
        <span>This page display all <code>orders</code> list</span>
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
                        <th>Position</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $order)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->profile?->position ?? 'No Position' }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->profile?->phone_number }}</td>
                        <td>{{ $order->profile?->address ?? 'No Address' }}</td>
                        <td>
                            <a href="{{ route('admin-orders-show', $order->id) }}" data-toggle="tooltip" data-placement="top" title="Show Order"
                                class="btn py-1 px-2 btn-primary"><i class="ti-eye"></i></a>
                            <a href="{{ route('admin-orders-delete', $order->id) }}" data-toggle="tooltip" data-placement="top" title="Delete Order"
                                class="btn py-1 px-2 btn-danger"><i class="ti-trash"></i></a>
                            <a href="{{ route('admin-orders-approve', $order->id) }}" data-toggle="tooltip" data-placement="top" title="Approve Order"
                                class="btn py-1 px-2 btn-success"><i class="ti-check"></i></a>
                            <a href="{{ route('admin-orders-park', $order->id) }}" data-toggle="tooltip" data-placement="top" title="Park Order"
                                class="btn py-1 px-2 btn-warning"><i class="ti-control-pause"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No orders available for display</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
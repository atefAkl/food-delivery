@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{__('Admins List')}}</h5>
        <span>This page display all <code>admins</code> list</span>
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
                    @forelse ($data as $admin)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->profile?->position ?? 'No Position' }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->profile?->phone_number }}</td>
                        <td>{{ $admin->profile?->address ?? 'No Address' }}</td>
                        <td>
                            <a href="{{ route('admin-admins-show', $admin->id) }}" data-toggle="tooltip" data-placement="top" title="Show Admin"
                                class="btn py-1 px-2 btn-primary"><i class="ti-eye"></i></a>
                            <a href="{{ route('admin-admins-delete', $admin->id) }}" data-toggle="tooltip" data-placement="top" title="Delete Admin"
                                class="btn py-1 px-2 btn-danger"><i class="ti-trash"></i></a>
                            <a href="{{ route('admin-admins-approve', $admin->id) }}" data-toggle="tooltip" data-placement="top" title="Approve Admin"
                                class="btn py-1 px-2 btn-success"><i class="ti-check"></i></a>
                            <a href="{{ route('admin-admins-park', $admin->id) }}" data-toggle="tooltip" data-placement="top" title="Park Admin"
                                class="btn py-1 px-2 btn-warning"><i class="ti-control-pause"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No admins available for display</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
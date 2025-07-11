<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    //
    public function index()
    {
        $data = User::where('type', 'delivery')->with('delivery')->get();
        return view('admin.partners.deliveries.index', compact('data'));
    }

    public function show($id)
    {
        $data = User::with('delivery')->find($id);
        return view('admin.partners.deliveries.show', compact('data'));
    }
}

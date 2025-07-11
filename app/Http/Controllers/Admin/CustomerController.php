<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {
        $data = User::where('type', 'customer')->with('customer')->get();
        return view('admin.partners.customers.index', compact('data'));
    }

    public function show($id)
    {
        $data = User::where('type', 'customer')->with('customer')->find($id);
        return view('admin.partners.customers.show', compact('data'));
    }
}

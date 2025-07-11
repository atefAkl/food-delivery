<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    //
    public function index()
    {
        $data = User::where('type', 'chef')->with('chef')->get();
        return view('admin.partners.restaurants.index', compact('data'));
    }

    public function show($id)
    {
        $data = User::with('chef')->find($id);
        return view('admin.partners.restaurants.show', compact('data'));
    }
}

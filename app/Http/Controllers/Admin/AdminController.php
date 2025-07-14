<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {
        $data = Admin::with('profile')->get();
        return view('admin.partners.admins.index', compact('data'));
    }

    public function show($id)
    {
        $data = Admin::with('profile')->find($id);
        return view('admin.partners.admins.show', compact('data'));
    }
}

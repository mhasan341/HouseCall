<?php

namespace App\Http\Controllers\Admin;

use App\Models\Drug;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    /// Returns the dashboard
    public function index()
    {
        $totalUsers = 0;

        $totalUsers = User::count();
        $totalDrugs = Drug::get()->count();

        return view('home', compact('totalUsers', 'totalDrugs'));
    }
}

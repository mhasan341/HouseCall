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
        $totalUsers = User::count();
        $totalDrugs = Drug::where('user_id', Auth::id())->count();
        return view('home', compact('totalUsers', 'totalDrugs'));
    }
}

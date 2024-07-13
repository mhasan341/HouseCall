<?php

namespace App\Http\Controllers\Admin;

class HomeController
{
    /// Returns the dashboard
    public function index()
    {
        return view('home');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HunterDashboardController extends Controller
{
    public function index()
    {
        $hunter = Auth::guard('hunter')->user();
        return view('hunter.hunterDashboard', compact('hunter'));
    }
}

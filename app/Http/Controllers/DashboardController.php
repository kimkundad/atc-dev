<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){

        $sum = 1;
        return view('admin.dashboard.index', compact('sum'));
    }
}

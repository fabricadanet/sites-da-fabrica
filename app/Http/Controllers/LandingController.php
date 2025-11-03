<?php
// app/Http/Controllers/LandingController.php

namespace App\Http\Controllers;

use App\Models\Plan;

class LandingController extends Controller
{
    public function index()
    {
       // $plans = Plan::active()->ordered()->get();
        
        return view('landing', [
            'plans' => [],
            'featuredPlan' =>'', // Plano Profissional
        ]);
    }
}
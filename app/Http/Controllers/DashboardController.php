<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the clinical dashboard with summary statistics.
     */
    public function index(Request $request): View
    {
        // Enterprise dummy stats for SehaLink Edara Task 1
        $stats = [
            'total_patients' => 1250,
            'today_appointments' => 42,
            'er_occupancy' => 8,
            'inpatients_count' => 156,
        ];

        return view('dashboard', compact('stats'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\Result;

class DashboardController extends Controller
{
    public function index()
    {
        $totalExams    = Exam::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalResults  = Result::count();

        return view('admin.dashboard', compact(
            'totalExams',
            'totalStudents',
            'totalResults'
        ));
    }
}
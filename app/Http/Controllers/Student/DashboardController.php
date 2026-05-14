<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $availableExams = Exam::where('status', 'active')->count();
        $myResults      = Result::where('user_id', Auth::id())->count();

        return view('student.dashboard', compact(
            'availableExams',
            'myResults'
        ));
    }
}
<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::where('user_id', Auth::id())
                         ->with('exam')
                         ->latest()
                         ->get();

        return view('student.results.index', compact('results'));
    }

    public function download(Result $result)
    {
        if ($result->user_id !== Auth::id()) {
            abort(403);
        }

        $result->load('exam', 'user');

        $pdf = Pdf::loadView('student.results.download', compact('result'));

        return $pdf->download('result-' . $result->exam->title . '.pdf');
    }
}   
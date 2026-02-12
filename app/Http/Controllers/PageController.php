<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
class PageController extends Controller
{
    public function home()
    {
        $courses = Course::with(['instructor.user', 'category'])
            ->latest()
            ->take(6)
            ->get();
            
        return view('home', compact('courses'));
    }
}

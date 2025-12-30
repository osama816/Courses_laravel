<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Http\Requests\StorecourseRequest;
use App\Http\Requests\UpdatecourseRequest;
use  App\Services\CourseServices;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{
    protected $CourseServices;

    public function __construct(CourseServices $CourseServices)
    {
        $this->CourseServices = $CourseServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = $this->CourseServices->getHomeCourses();
        return view('home', ['courses' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecourseRequest $request) {}

    /**
     * Display the specified resource.
     */
    public function show(course $course)
    {
        $course = $this->CourseServices->getCourse($course->id);
        return view("courses.course_details", ['course' => $course]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecourseRequest $request, course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(course $course)
    {
        //
    }
    public function search(Request $request)
    {
        $courses = $this->CourseServices->searchCourse($request);
        return $courses;
    }
}

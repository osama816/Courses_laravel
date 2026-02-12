<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
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
    public function store(StoreCourseRequest $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course = $this->CourseServices->getCourse($course->id);
        return view("courses.course_details", ['course' => $course]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
    public function search(Request $request)
    {
        $courses = $this->CourseServices->searchCourse($request);
        return $courses;
    }
}

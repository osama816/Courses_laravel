<?php

namespace App\Services;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCourseRequest;

class CourseServices
{

    public function createCourse(array $data, StoreCourseRequest $StoreCourseRequest): Course
    {
         $data = $StoreCourseRequest->validated();
        $data['user_id'] = Auth::id();
        $course = Course::create( $data);
        return $course;
    }
    public function getCourse($id){
        $Course = Course::find($id);
        return $Course;
    }

    public function searchCourse(Request $request){
        $search = Course::query()->
        when( $request->search, function($query) use ($request){
            $query->where('title', 'like', '%'.$request->search.'%')->
            orWhere('description', 'like', '%'.$request->search.'%');
        })->get();
        return $search;
    }
    public function getHomeCourses()
{
    //return Cache::rememberForever('courses', function () {
        return Course::with(['instructor.user', 'category'])
            ->latest()
            ->take(6)
            ->get();
    //});
}


}

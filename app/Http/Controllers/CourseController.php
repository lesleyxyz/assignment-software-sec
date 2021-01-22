<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

# References:
# https://jetstream.laravel.com/1.x/features/api.html
# https://laravel.com/docs/8.x/responses
# https://laravel.com/docs/8.x/sanctum
# https://laravel.com/docs/8.x/eloquent
# https://restfulapi.net/http-methods/

class CourseController extends Controller
{

    function create(Request $request){
        if (!$request->user()->tokenCan('create'))
            return response('{"err": "Provided token has not got access to create"}', 401)->header('Content-Type', 'application/json');

        $json = $request->json()->all();

        $newCourse = new Course();
        $newCourse->name = $json["name"];
        $newCourse->teacher = $json["teacher"];
        $newCourse->location = $json["location"];
        $newCourse->save();

        return $newCourse;
    }

    function read(Request $request, $id = null){
        if($id === null)
            return Course::all();

        $course = Course::find($id);
        if(!$course)
            return response('{"err": "Provided course id does not exist"}', 404)->header('Content-Type', 'application/json');

        return $course;
    }

    function update(Request $request, $id){
        if (!$request->user()->tokenCan('update'))
            return response('{"err": "Provided token has not got access to update"}', 401)->header('Content-Type', 'application/json');

        $course = Course::find($id);
        if(!$course)
            return response('{"err": "Provided course id does not exist"}', 404)->header('Content-Type', 'application/json');

        $json = $request->json()->all();
        $allowed_values = ["name", "teacher", "location"];

        foreach($allowed_values as $value)
            if(array_key_exists($value, $json))
                $course[$value] = $json[$value];

        $course->save();

        return $course;
    }

    function delete(Request $request, $id){
        if (!$request->user()->tokenCan('delete'))
            return response('{"err": "Provided token has not got access to delete"}', 401)->header('Content-Type', 'application/json');

        $course = Course::find($id);
        if(!$course)
            return response('{"err": "Provided course id does not exist"}', 404)->header('Content-Type', 'application/json');

        $course->delete();

        return response('{"deleted": 1}', 200)->header('Content-Type', 'application/json');
    }
}

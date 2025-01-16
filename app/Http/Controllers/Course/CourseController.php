<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{

    public function enrollCourse(Request $request) {
        try {

            $user = Auth::user();

            if ($user->userable_type == 'App\Models\Teacher') {
                return redirect()->back()->withErrors(['error' => 'Teachers cannot enroll in courses']);
            }
    
            // Find course by class code
            $course = Course::where('class_code', $request->input('class_code'))->first();
    
            // Check if course exists
            if (!$course) {
                return redirect()->back()->withErrors(['error' => 'Course not found']);
            }
    
            // Check if user is already enrolled in course
            if ($user->userable->courses->contains($course)) {
                return redirect()->back()->withErrors(['error' => 'You are already enrolled in this course']);
            }
    
            // Enroll user in course
            $user->userable->courses()->attach($course);
    
            $courses = $user->userable->courses;
        
            return redirect()->route('courses', compact('courses'));
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error enrolling in course']);
        }
    }

    public function unenrollCourse(Request $request, string $courseId) {
        $user = Auth::user();

        if ($user->userable_type == 'App\Models\Teacher') {
            return redirect()->back()->withErrors(['error' => 'Teachers cannot unenroll from courses']);
        }

        $user->userable->courses()->detach($courseId);
        $courses = $user->userable->courses;

        return redirect()->route('courses', compact('courses'));
    }

    public function showCourses(Request $request): View
    {
        $user = Auth::user();

        if ($user && $user->userable_type == 'App\Models\Student') {
            $courses = $user->userable->courses()->orderBy('created_at', 'desc')->get();

            $search = $request->input('search');
            $subject = $request->input('subject');
            $page = $request->input('page') ?? 1;
            $take = $request->input('take') ?? 10;
            $availablePages = ceil($courses->count() / $take);
            $skip = ($page - 1) * $take;

            if ($search) {
                $courses = $courses->filter(function ($course) use ($search) {
                    return strpos($course->name, $search) !== false;
                });
            }

            if ($subject && $subject != 'all') {
                $courses = $courses->filter(function ($course) use ($subject) {
                    return $course->subject == $subject;
                });
            }

            $courses = $courses->slice($skip)->take($take);

            return view('course.courses', compact('courses', 'availablePages', 'page', 'take', 'search', 'subject'));
        }

        // Get courses by teacher id
        $courses = Course::where('teacher_id', $user->userable->id)->orderBy('created_at', 'desc')->get();

        // Paginate
        $search = $request->input('search');
        $subject = $request->input('subject');
        $page = $request->input('page') ?? 1;
        $take = $request->input('take') ?? 10;
        $availablePages = ceil($courses->count() / $take);
        $skip = ($page - 1) * $take;

        if ($search) {
            $courses = $courses->filter(function ($course) use ($search) {
                return strpos($course->name, $search) !== false;
            });
        }

        if ($subject && $subject != 'all') {
            $courses = $courses->filter(function ($course) use ($subject) {
                return $course->subject == $subject;
            });
        }

        $courses = $courses->slice($skip)->take($take);

        return view('course.courses', compact('courses', 'availablePages', 'page', 'take', 'search', 'subject'));
    }

    public function showCourse(
        Request $request,
        string $id
    ): View {
        $course = Course::findOrFail($id);
        $courseSections = CourseSection::with(['courseItems', 'courseItems.courseItemProgress' => function ($query) {
            $query->where('user_id', Auth::id());
        }])->where('course_id', $id)->orderBy('created_at', 'asc')->get();

        // Hide private course sections if user is a student
        if (Auth::user()->userable_type == 'App\Models\Student') {
            $courseSections = $courseSections->filter(function ($courseSection) {
                return $courseSection->is_public;
            });

            // Filter course items too
            $courseSections = $courseSections->map(function ($courseSection) {
                $courseSection->courseItems = $courseSection->courseItems->filter(function ($courseItem) {
                    return $courseItem->is_public;
                })->map(function ($courseItem) {
                    if (!$courseItem->courseItemProgress) {
                        $courseItem->is_completed = false;
                    } else {
                        $courseItem->is_completed = $courseItem->courseItemProgress->is_completed;
                    }
                    return $courseItem;
                });

                return $courseSection;
            });
        }

        $tab = $request->input('tab');

        $isEdit = false;

        return view('course.course_detail', compact('course', 'courseSections', 'tab', 'isEdit'));
    }

    public function showCourseEdit(
        Request $request,
        string $id
    ): View {
        try {
            $course = Course::findOrFail($id);
            $courseSections = CourseSection::with('courseItems')->where('course_id', $id)->orderBy('created_at', 'asc')->get();
    
            $tab = $request->input('tab');

            $isEdit = true && Auth::user()->userable_type == 'App\Models\Teacher';
    
            return view('course.course_detail', compact('course', 'courseSections', 'tab', 'isEdit'));
        }
        catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error loading course']);
        }
    }
        

    public function createNewCourse(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => ['required', 'string'],
            'class_code' => ['required', 'string', 'size:5'],
        ]);

        // Log current user
        $user = Auth::user();

        try {
            // Make sure class code is unique
            if (Course::where('class_code', $request->input('class_code'))->exists()) {
                return redirect()->back()->withErrors(['error' => 'Class code already exists']);
            }

            Course::create([
                'teacher_id' => $user->userable->id,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'subject' => $request->input('subject'),
                'class_code' => $request->input('class_code'),
            ]);

            $courses = Course::get();

            return redirect()->route('courses', compact('courses'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error creating course']);
        }
    }

    public function deleteCourse(string $id)
    {
        try {
            Course::findOrFail($id)->delete();
            return redirect()->route('courses');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error deleting course']);
        }
    }

    public function updateCourse(Request $request, string $id)
    {
        // Validate input
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'class_code' => ['required', 'string', 'size:5'],
        ]);

        try {
            // Make sure class code is unique
            if (Course::where('class_code', $request->input('class_code'))->where('id', '!=', $id)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Class code already exists']);
            }
            
            Course::findOrFail($id)->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'class_code' => $request->input('class_code'),
            ]);

            return redirect()->route('course.show.edit', ['id' => $id]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error updating course']);
        }

    }
}

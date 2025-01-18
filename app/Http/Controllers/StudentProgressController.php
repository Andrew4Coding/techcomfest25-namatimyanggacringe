<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Submission;
use Illuminate\Http\Request;

class StudentProgressController extends Controller
{
    public function show(Request $request) {
        $user = $request->user();
        $courses = $user->userable->courses()->get();

        $courseId = $request->input('course_id', $courses->first()->id);
        $course = $courses->find($courseId);

        $topStudents = [];

        $search = $request->input('search');

        foreach ($course->students as $student) {
            if ($search && stripos($student->user->name, $search) === false) {
                continue;
            }

            $studentTotalAssignment = 0;
            $studentSubmissionSum = 0;

            $submissionGrades = [];

            foreach ($course->courseSections as $courseSection) {
                foreach ($courseSection->courseItems as $courseItem) {
                    if ($courseItem->course_itemable_type === Submission::class) {
                        // Take last submission item grade for the student
                        $submissionItem = $courseItem->courseItemable->submissionItems->where('student_id', $student->id)->last();

                        $studentSubmissionSum += $submissionItem ? $submissionItem->grade : 0;
                        $studentTotalAssignment += 1;

                        $submissionGrade = new \stdClass();
                        $submissionGrade->name = $courseItem->name;
                        $submissionGrade->grade = $submissionItem ? $submissionItem->grade : 0;
                        $submissionGrades[] = $submissionGrade;
                    }

                    // TODO QUIZ
                    else if ($courseItem->course_itemable_type === Quiz::class) {
                        $studentTotalAssignment += 1;
                    }
                }
            }


            $student->rank = count($topStudents) + 1;
            $student->averageScore = $studentTotalAssignment > 0 ? $studentSubmissionSum / $studentTotalAssignment : 0;
            $student->submissionGrades = $submissionGrades;

            $topStudents[] = $student;
        }

        // Sort students by average score
        usort($topStudents, function ($a, $b) {
            return $a->averageScore - $b->averageScore;
        });

        // Paginate
        $page = $request->input('page') ?? 1;
        $take = $request->input('take') ?? 10;
        $availablePages = ceil(count($topStudents) / $take);
        $skip = ($page - 1) * $take;

        // Slice students
        $topStudents = array_slice($topStudents, $skip, $take);

        return view('student_progress.index', compact('courses', 'course', 'topStudents', 'search', 'page', 'take', 'availablePages'));
    }
}

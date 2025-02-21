<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\StudentMessage;
use App\Models\Submission;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->userable_type == Teacher::class) {
            return DashboardController::showTeacherDashboard($request);
        } else {
            return DashboardController::showStudentDashboard($request);
        }
    }

    public function showTeacherDashboard(Request $request)
    {
        $user = $request->user();

        $courses = $user->userable->courses;

        // Count jumlah course item for each courseItem inside course
        foreach ($courses as $course) {
            $totalCourseItem = 0;
            $courseItemProgressCount = 0;
            $assignmentProgressCount = 0;
            $totalAssignment = 0;
            $submissionSum = 0;

            // Count average score for each student in course
            $topStudents = [];

            foreach ($course->students as $student) {
                $studentTotalAssignment = 0;
                $studentSubmissionSum = 0;

                foreach ($course->courseSections as $courseSection) {
                    foreach ($courseSection->courseItems as $courseItem) {
                        if ($courseItem->course_itemable_type === Submission::class) {
                            // Take last submission item grade for the student
                            $submissionItem = $courseItem->courseItemable->submissionItems->where('student_id', $student->id)->last();

                            $studentSubmissionSum += $submissionItem ? $submissionItem->grade : 0;
                            $studentTotalAssignment += 1;
                        }

                        // TODO QUIZ
                        else if ($courseItem->course_itemable_type === Quiz::class) {
                            $studentTotalAssignment += 1;
                        }
                    }
                }

                $student->averageScore = $studentTotalAssignment > 0 ? $studentSubmissionSum / $studentTotalAssignment : 0;

                $topStudents[] = $student;
            }

            // Sort students by average score
            usort($topStudents, function ($a, $b) {
                return $a->averageScore - $b->averageScore;
            });


            $studentCount = $course->students->count();

            $attendanceRates = [];

            foreach ($course->courseSections as $courseSection) {
                foreach ($courseSection->courseItems as $courseItem) {
                    if ($courseItem->is_public === false) {
                        continue;
                    }

                    $totalCourseItem += 1;
                    $courseItemProgressCount += $courseItem->courseItemProgress ? 1 : 0;

                    if ($courseItem->course_itemable_type  === Submission::class) {

                        if ($courseItem->courseItemProgress) {
                            $assignmentProgressCount += 1;
                        }

                        $totalAssignment += 1;

                        foreach ($courseItem->courseItemable->submissionItems as $submissionItem) {
                            $submissionSum += $submissionItem->grade ?? 0;
                        }
                    } else if ($courseItem->course_itemable_type === Quiz::class) {
                        if ($courseItem->courseItemProgress) {
                            $assignmentProgressCount += 1;
                        }

                        $totalAssignment += 1;
                    }

                    else if ($courseItem->course_itemable_type === Attendance::class) {
                        $attendance = $courseItem->courseItemable;
                        $attendanceSubmissionCount = $attendance->submissions->count();

                        $rate = $attendanceSubmissionCount / $studentCount;

                        $attendanceRate = new \stdClass();
                        $attendanceRate->date = $attendance->created_at;
                        $attendanceRate->rate = $rate;
                        $attendanceRates[] = $attendanceRate;
                    }
                }
            }

            $topStudents = array_slice($topStudents, 0, 10);
            $course->topStudents = $topStudents;

            $course->totalCourseItem = $totalCourseItem;
            $course->courseItemProgressCount = $courseItemProgressCount;
            $course->assignmentProgressCount = $assignmentProgressCount;
            $course->totalAssignment = $totalAssignment;

            $course->averageScore = $totalAssignment > 0 ? $submissionSum / $totalAssignment : 0;

            // Get top 5 latest attendance rates
            $course->attendanceRates = $attendanceRates;
        }

        // List all submission with closest due date inside course
        $deadlines = [];
        foreach ($courses as $course) {
            $studentCount = $course->students->count();
            foreach ($course->courseSections as $courseSection) {
                foreach ($courseSection->courseItems as $courseItem) {
                    $submissionCount = 0;
                    // log each course item that has submission
                    if ($courseItem->course_itemable_type === Submission::class) {
                        // iterate over all submission item and get how many submission item that has been submitted
                        foreach ($courseItem->courseItemable->submissionItems as $submissionItem) {
                            $submissionCount += 1;
                        }
                        // Set corresponding submission 
                        $deadlines[] = [
                            'id' => $courseItem->courseItemable->id,
                            'course' => $course,
                            'courseItem' => $courseItem,
                            'submission' => $courseItem->courseItemable,
                            'submissionCount' => $submissionCount,
                            'studentCount' => $studentCount,
                        ];
                    }
                }
            }
        }

        // Sort deadlines by closest one
        usort($deadlines, function ($a, $b) {
            return strtotime($a['submission']->due_date) - strtotime($b['submission']->due_date);
        });

        // Get All students inside all course that user has
        $students = [];
        foreach ($courses as $course) {
            foreach ($course->students as $student) {
                // Prevent Student Duplication
                if (in_array($student, $students)) {
                    continue;
                }
                $students[] = $student;
            }
        }

        $deadlines = array_slice($deadlines, 0, 4);

        return view('dashboard.dashboard', compact('courses', 'deadlines', 'students'));
    }


    public function showStudentDashboard(Request $request)
    {
        $user = $request->user();

        $courses = $user->userable->courses;

        // Count jumlah course item for each courseItem inside course
        foreach ($courses as $course) {
            $totalCourseItem = 0;
            $courseItemProgressCount = 0;
            $assignmentProgressCount = 0;
            $totalAssignment = 0;
            $submissionSum = 0;

            foreach ($course->courseSections as $courseSection) {
                if ($courseSection->is_public === false) {
                    continue;
                }
                foreach ($courseSection->courseItems as $courseItem) {
                    if ($courseItem->is_public === false) {
                        continue;
                    }

                    $totalCourseItem += 1;
                    $courseItemProgressCount += $courseItem->courseItemProgress ? 1 : 0;

                    if ($courseItem->course_itemable_type === Submission::class) {
                        if ($courseItem->courseItemProgress) {
                            $assignmentProgressCount += 1;
                        }

                        $totalAssignment += 1;

                        foreach ($courseItem->courseItemable->submissionItems as $submissionItem) {
                            $submissionSum += $submissionItem->grade ?? 0;
                        }
                    } else if ($courseItem->course_itemable_type === Quiz::class) {
                        if ($courseItem->courseItemProgress) {
                            $assignmentProgressCount += 1;
                        }

                        $totalAssignment += 1;
                    }
                }
            }

            $course->totalCourseItem = $totalCourseItem;
            $course->courseItemProgressCount = $courseItemProgressCount;
            $course->assignmentProgressCount = $assignmentProgressCount;
            $course->totalAssignment = $totalAssignment;

            $course->averageScore = $totalAssignment > 0 ? $submissionSum / $totalAssignment : 0;
        }

        // List all submission with closest due date inside course
        $deadlines = [];
        foreach ($courses as $course) {
            $studentCount = $course->students->count();
            foreach ($course->courseSections as $courseSection) {
                foreach ($courseSection->courseItems as $courseItem) {
                    $submissionCount = 0;
                    // log each course item that has submission
                    if ($courseItem->course_itemable_type === Submission::class) {
                        // iterate over all submission item and get how many submission item that has been submitted
                        foreach ($courseItem->courseItemable->submissionItems as $submissionItem) {
                            $submissionCount += 1;
                        }
                        // Set corresponding submission 
                        $deadlines[] = [
                            'id' => $courseItem->courseItemable->id,
                            'course' => $course,
                            'courseItem' => $courseItem,
                            'submission' => $courseItem->courseItemable,
                            'submissionCount' => $submissionCount,
                            'studentCount' => $studentCount,
                        ];
                    }
                }
            }
        }

        // Sort deadlines by closest one
        usort($deadlines, function ($a, $b) {
            return strtotime($a['submission']->due_date) - strtotime($b['submission']->due_date);
        });

        // Filter top 3 closest deadlines
        $deadlines = array_slice($deadlines, 0, 5);

        // Show All Pesan Dari guru
        $studentMessages = StudentMessage::where('student_id', $user->userable->id)->get();

        return view('dashboard.dashboard', compact('courses', 'deadlines', 'studentMessages'));
    }

    public function sendStudentMessage(Request $request, string $studentId)
    {
        try {
            $user = $request->user();
            $student = Student::findOrFail($studentId);

            $request->validate([
                'message' => 'required|string',
            ]);

            // Create new studentMessage 
            $studentMessage = new StudentMessage();
            $studentMessage->student_id = $student->id;
            $studentMessage->teacher_id = $user->userable->id;
            $studentMessage->message = $request->message;
            $studentMessage->save();


            return back()->with('success', 'Message sent successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send message');
        }
    }
}

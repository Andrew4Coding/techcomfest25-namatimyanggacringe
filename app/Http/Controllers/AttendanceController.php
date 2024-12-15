<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSubmission;
use App\Models\CourseSection;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function show(Request $request, string $id)
    {
        $attendance = Attendance::findOrFail($id);

        $role = $request->user()->userable_type;

        if ($role === 'App\Models\Student') {
            $alreadySubmitted = $attendance->submissions->contains('student_id', $request->user()->userable->id);
            return view('attendance.show', compact('attendance', 'alreadySubmitted'));
        }

        $attendanceSubmissions = $attendance->submissions;

        return view('attendance.show', compact('attendance', 'attendanceSubmissions'));
    }

    public function store(Request $request, $courseSectionId)
    {
        $courseSection = CourseSection::findOrFail($courseSectionId);

        $attendanceitem = new Attendance();
        $attendanceitem->password = $request->input('password');
        $attendanceitem->save();

        $attendanceitem->courseItem()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'course_section_id' => $courseSectionId,
        ]);

        $courseSection = CourseSection::findOrFail($courseSectionId);
        $courseId = $courseSection->course_id;

        return redirect()->route('course.show.edit', ['id' => $courseId]);
    }

    public function update(Request $request, string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->courseItem->name = $request->input('name');
        $attendance->courseItem->description = $request->input('description');
        $attendance->password = $request->input('password');
        $attendance->save();

        return redirect()->route('attendance.show', ['id' => $id]);
    }

    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('course.show.edit', ['id' => $attendance->courseItem->course_section_id]);
    }

    public function submitAttendance(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'status' => ['required', 'string', 'in:present,absent,late'],
            'password' => ['required', 'string'],
        ]);

        $attendance = Attendance::findOrFail($id);

        // Check if already attended
        if ($attendance->submissions->contains('student_id', $request->user()->userable->id)) {
            return redirect()->back()->withErrors('You have already submitted your attendance.');
        }
        
        // Check for password
        if ($attendance->password !== $request->input('password')) {
            return redirect()->back()->withErrors('Invalid Attendance password.');
        }

        // Create new Attendance Submission
        AttendanceSubmission::create([
            'attendance_id' => $id,
            'student_id' => $request->user()->userable->id,
            'status' => $request->input('status'),
        ]);

        return redirect()->back();
    }
}

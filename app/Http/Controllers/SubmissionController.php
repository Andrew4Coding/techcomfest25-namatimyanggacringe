<?php

namespace App\Http\Controllers;

use App\Models\CourseItem;
use App\Models\CourseSection;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    public function show(string $submissionId) {
        try {
            $submission = Submission::findOrFail($submissionId);

            return view('submission.show', compact('submission'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors('Submission not found.');
        }
    }

    public function createSubmissionField(Request $request, string $courseSectionId) {
        try {
            $request->validate([
                'name' => ['required', 'string'],
                'description' => ['string'],
                'content' => ['required', 'string'],
                'opened_at' => ['required', 'date'],
                'due_date' => ['required', 'date'],
                'file_types'=> ['required', 'string'],
            ]);

            $newCourseItem = new Submission();
            $newCourseItem->content = $request->input('content');
            $newCourseItem->opened_at = $request->input('opened_at');
            $newCourseItem->due_date = $request->input('due_date');
            $newCourseItem->file_types = $request->input('file_types');
            $newCourseItem->save();

            $newCourseItem->courseItem()->create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'course_section_id' => $courseSectionId,
            ]);

            $courseSection = CourseSection::findOrFail($courseSectionId);
            $courseId = $courseSection->course_id;

            return redirect()->route('course.show', ['id' => $courseId]);
        } catch (\Exception $e) {
            Log::error('Error creating submission field: ' . $e->getMessage());
            dd($e);
            return redirect()->back()->withErrors('Failed to create submission field.');
        }
    }

    public function deleteSubmissionField(string $submissionId) {
        try {
            $submission = Submission::findOrFail($submissionId);
            $courseId = $submission->courseItem->courseSection->course_id;
            $submission->delete();
            return redirect()->route('course.show', ['id' => $courseId]);
        } catch (\Exception $e) {
            Log::error('Error deleting submission field: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to delete submission field.');
        }
    }
}

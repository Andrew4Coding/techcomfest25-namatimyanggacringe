<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    public function show(Request $request, string $submissionId) {
        try {
            $submission = Submission::findOrFail($submissionId);

            // Show user submission
            $submissionItems = $submission->submissionItems()->where('student_id', $request->user()->userable_id)->get();

            return view('submission.show', compact('submission', 'submissionItems'));
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

    public function updateSubmissionField(Request $request, string $submissionId) {
        try {
            $submission = Submission::findOrFail($submissionId);

            $request->validate([
                'name' => ['required', 'string'],
                'description' => ['string'],
                'content' => ['required', 'string'],
                'opened_at' => ['required', 'date'],
                'due_date' => ['required', 'date'],
                'file_types'=> ['required', 'string'],
            ]);

            $submission->content = $request->input('content');
            $submission->opened_at = $request->input('opened_at');
            $submission->due_date = $request->input('due_date');
            $submission->file_types = $request->input('file_types');
            $submission->save();

            $submission->courseItem()->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

            return redirect()->route('submission.show', ['submissionId' => $submissionId]);
        } catch (\Exception $e) {
            Log::error('Error updating submission field: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to update submission field.');
        }
    }
}

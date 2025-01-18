<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\Submission;
use App\Models\SubmissionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    public function show(Request $request, string $submissionId)
    {
        try {
            $role = $request->user()->userable_type;
            $submission = Submission::findOrFail($submissionId);
            // Get all submission items with its student           
            if ($role === 'App\Models\Teacher') {
                $submissionItems = $submission->submissionItems()->with('student')->get();

                return view('submission.show', compact('submission', 'submissionItems'));
            } else {
                // Show user submission
                $submissionItem = $submission->submissionItems()
                    ->where('student_id', $request->user()->userable_id)
                    ->first();

                // Check if the student has used all their chances
                $fullOfChances = $submissionItem && $submissionItem->attempts >= $submission->max_attempts;

                // Validate submission dates
                $submissionOpen = $submission->opened_at && $submission->opened_at->isPast();
                $submissionDue = $submission->due_date && $submission->due_date->isFuture();

                // Determine if the student can submit
                $canSubmit = true;
                return view('submission.show', compact('submission', 'submissionItem', 'canSubmit'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage());
        }
    }

    public function createSubmissionField(Request $request, string $courseSectionId)
    {
        try {
            $request->validate([
                'name' => ['required', 'string'],
                'content' => ['required', 'string'],
                'opened_at' => ['required', 'date'],
                'due_date' => ['required', 'date'],
                'file_types' => ['required', 'string'],
            ]);

            // Validate due date cant be before opened date
            if ($request->input('due_date') < $request->input('opened_at')) {
                return redirect()->back()->withErrors('Due date cannot be before opened date.');
            }

            $newCourseItem = new Submission();
            $newCourseItem->content = $request->input('content');
            $newCourseItem->opened_at = $request->input('opened_at');
            $newCourseItem->due_date = $request->input('due_date');
            $newCourseItem->file_types = $request->input('file_types');
            $newCourseItem->max_attempts = $request->input('max_attempts') ?? 10; 
            $newCourseItem->save();

            $newCourseItem->courseItem()->create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'course_section_id' => $courseSectionId,
            ]);

            $courseSection = CourseSection::findOrFail($courseSectionId);
            $courseId = $courseSection->course_id;

            return redirect()->route('course.show.edit', ['id' => $courseId]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to create submission field: ' . $e->getMessage());
        }
    }

    public function deleteSubmissionField(string $submissionId)
    {
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

    public function updateSubmissionField(Request $request, string $submissionId)
    {
        try {
            $submission = Submission::findOrFail($submissionId);

            $request->validate([
                'name' => ['required', 'string'],
                'description' => ['string'],
                'content' => ['required', 'string'],
                'opened_at' => ['required', 'date'],
                'due_date' => ['required', 'date'],
                'file_types' => ['required', 'string'],
            ]);

            // Validate due date cant be before opened date
            if ($request->input('due_date') < $request->input('opened_at')) {
                return redirect()->back()->withErrors('Due date cannot be before opened date.');
            }

            $submission->content = $request->input('content');
            $submission->opened_at = $request->input('opened_at');
            $submission->due_date = $request->input('due_date');
            $submission->file_types = $request->input('file_types');
            $submission->max_attempts = $request->input('max_attempts') ?? 10;
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

    public function displaySubmission(Request $request, string $submissionItemId)
    {
        try {
            $submissionItem = SubmissionItem::findOrFail($submissionItemId);
            $submission = $submissionItem->submission;
            $user = $request->user();

            $role = $user->userable_type;

            return view('submission.display', compact('submissionItem'));
        } catch (\Exception $e) {
            Log::error('Error displaying submission: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to display submission.');
        }
    }
}

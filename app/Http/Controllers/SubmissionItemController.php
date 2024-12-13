<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubmissionItemController extends Controller
{
    public function submitToSubmission(Request $request, string $submissionId) {
        try {
            $user = $request->user();

            $request->validate([
                'file' => ['required', 'file'],
            ]);

            $submission = Submission::findOrFail($submissionId);

            $file = $request->file('file');
            $file->storeAs('submissions', $file->getClientOriginalName());

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $course_section_id = $submission->courseItem->courseSection->id; // Assuming course_section_id is a property of Submission
            $filePath = $file->storeAs("uploads/submissions/{$course_section_id}/{$submissionId}/{$user->id}", $fileName, 's3');

            $url = env('AWS_URL') . $filePath;

            // Find related submission item by submissionid and user and edit it if it exists
            $submissionItem = SubmissionItem::where('submission_id', $submissionId)->where('student_id', $user->userable_id)->first();

            if ($submissionItem) {
                $submissionItem->submission_urls = $url;
                $submissionItem->attempts += 1;
                $submissionItem->save();
            } else {
                $submissionItem = new SubmissionItem();
                $submissionItem->submission_id = $submissionId;
                $submissionItem->student_id = $user->userable_id;
                $submissionItem->submission_urls = $url;
                $submissionItem->save();
            }

            return redirect()->route('submission.show', ['submissionId' => $submissionId]);

        } catch (\Exception $e) {
            Log::error('Error submitting to submission: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to submit to submission.');
        }
    }
}

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

            $url = env('AWS_PATH') . $filePath;

            $submissionItem = SubmissionItem::create([
                'grade' => 0,
                'comment' => '',
                'submission_urls' => $url,
                'student_id' => $user->userable_id,
                'submission_id' => $submissionId,
            ]);




            return redirect()->route('submission.show', ['id' => $submissionId]);

        } catch (\Exception $e) {
            Log::error('Error submitting to submission: ' . $e->getMessage());
            dd($e);
            return redirect()->back()->withErrors('Failed to submit to submission.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function show(string $submissionId) {
        $submission = Submission::findOrFail($submissionId);
        return view('submissions.show', compact('submission'));
    }
}

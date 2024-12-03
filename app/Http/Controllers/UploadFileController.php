<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    // Upload file to s3 aws
    public function uploadFile(Request $request, string $courseId)
    {
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs("uploads/{$courseId}", $fileName, 's3');
        return response()->json(['file_path' => $filePath, 'course_id' => $courseId]);
    }
}

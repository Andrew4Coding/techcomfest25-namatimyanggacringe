<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;

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

    public function showFileForm() 
    {
        return view('upload');
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        // Store the uploaded file
        $filePath = $request->file('pdf')->store('uploads');

        // Extract text from the PDF
        $text = Pdf::getText(storage_path('app/' . $filePath));

        return response()->json([
            'message' => 'PDF uploaded successfully!',
            'text_content' => $text,
        ]);
    }
}

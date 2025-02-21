<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'pdf' => 'required|mimes:pdf|max:2048', // Validate file type and size
        ]);

        try {
            // Upload the file to S3
            if ($request->hasFile('pdf')) {
                $file = $request->file('pdf');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                // Sanitize the file name
                $fileName = basename($fileName);  // Remove any path components that might be included in the filename

                // Store on S3
                $filePath = Storage::disk('s3')->putFileAs('pdfs', $file, $fileName);

                // Generate the URL for the uploaded file on S3
                $fileUrl = Storage::disk('s3')->url('pdfs/' . $fileName);

                // Get the file content from S3
                $fileContent = Storage::disk('s3')->get('pdfs/' . $fileName);

                // Create a temporary path for the file
                $tempPath = storage_path('app/temp/' . $fileName);

                // Check if the 'temp' directory exists and create it if necessary
                if (!is_dir(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0775, true);
                }

                // Save the file content to the temporary location
                file_put_contents($tempPath, $fileContent);

                // Extract text from the downloaded file
                $text = Pdf::getText($tempPath);

                // Clean up the temporary file
                unlink($tempPath);

                return view('upload')->with('result', $text);
            } else {
                return back()->withErrors(['error' => 'No file uploaded.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to process the PDF: ' . $e->getMessage()]);
        }
    }

    public function getTextFromPDF($file)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        // Sanitize the file name
        $fileName = basename($fileName);  // Remove any path components that might be included in the filename

        // Store on S3
        $filePath = Storage::disk('s3')->putFileAs('pdfs', $file, $fileName);

        // Generate the URL for the uploaded file on S3
        $fileUrl = Storage::disk('s3')->url('pdfs/' . $fileName);

        // Get the file content from S3
        $fileContent = Storage::disk('s3')->get('pdfs/' . $fileName);

        // Create a temporary path for the file
        $tempPath = storage_path('app/temp/' . $fileName);

        // Check if the 'temp' directory exists and create it if necessary
        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0775, true);
        }

        // Save the file content to the temporary location
        file_put_contents($tempPath, $fileContent);

        // Extract text from the downloaded file
        $text = Pdf::getText($tempPath);

        // Clean up the temporary file
        unlink($tempPath);

        return $text;
    }
}

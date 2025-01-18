<?php

namespace App\Http\Controllers;

use App\Jobs\CheckSubmission;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Quiz;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function submitQuiz(Request $request, string $quizId)
    {
        if ($request->user()->userable_type !== Student::class) return redirect('/');

        $studentId = $request->user()->userable_id;

        CheckSubmission::dispatch($quizId, $studentId);

        return redirect('/');
    }

    public function showQuiz()
    {
        return view('quiz.quiz');
    }

    public function showQuizSummary(string $id)
    {
        $quiz = Quiz::where("id", $id)->withCount('quizSubmissions')->first();
        return view('quiz.quiz_summary', compact('quiz'));
    }

    public function showQuizSession(string $id, Request $request)
    {
        $page = $request->get('page', 1);

        $quiz = Quiz::with(['questions' => function ($query) {
            $query->select('id', 'content', 'quiz_id');
        }, 'questions.questionChoices' => function ($query) {
            $query->select('content', 'question_id');
        }])->withCount('questions')->where('id', $id)->first();

        $questionCount = $quiz->questions_count;

        if ($page < 1 || $page > $questionCount) {
            $page = 1;
        }

        return view('quiz.quiz', [
            'id' => $id,
            'page' => $page,
            'questionCount' => $questionCount,
            'quiz' => $quiz,
        ]);
    }

    public function showQuizCreation(string $courseId, Request $request)
    {
        return view('quiz.quiz_create');
    }

    public function showQuizAlteration(string $courseId, string $id, Request $request)
    {
        return view('quiz.quiz_alter', ['id' => $id]);
    }

    public function parseQuestionsFromCSV(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('csv_file');

        // Open the file for reading
        $fileHandle = fopen($file->getRealPath(), 'r');

        // Read the CSV header row
        $header = fgetcsv($fileHandle);

        $data = [];

        // Read each row of the CSV file
        while (($row = fgetcsv($fileHandle)) !== false) {
            // Combine header and row to create an associative array
            $data[] = array_combine($header, $row);
        }

        // Close the file
        fclose($fileHandle);

        // Convert the array to JSON
        $json = json_encode($data, JSON_PRETTY_PRINT);

        // Return create view with context
        return view('quiz.quiz_create', ['text' => $data]);
    }


    public function generateQuestionsFromPDF(Request $request)
    {

        // Store the file
        $pdfPath = $request->file('file')->store('pdfs');

        // Path to the uploaded PDF
        $pdfPath = storage_path('app/' . $pdfPath);

        // Convert PDF to text
        try {
            $text = Pdf::getText($pdfPath);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to convert PDF to text: ' . $e->getMessage()]);
        }


        // Return or store the text
        return response()->json(['text' => $text]);
    }

    public function store(Request $request, string $courseSectionId)
    {
        try {

            // Validate the request
            $request->validate([
                'name' => 'required|string',
                'content' => 'required|string',
                'start' => 'required|date',
                'duration' => 'required|integer',
            ]);

            $newQuizItem = new Quiz();
            $newQuizItem->id = (string)Str::uuid();
            $newQuizItem->start = $request->input('start');
            $newQuizItem->duration = $request->input('duration');

            // Determine finish time by start time and duration
            $newQuizItem->finish = date('Y-m-d H:i:s', strtotime($newQuizItem->start . ' + ' . $newQuizItem->duration . ' minutes'));
            $newQuizItem->save();

            $newQuizItem->courseItem()->create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'course_section_id' => $courseSectionId,
            ]);

            $courseSection = CourseSection::findOrFail($courseSectionId);
            $courseId = $courseSection->course_id;

            return redirect()->route('course.show.edit', ['id' => $courseId]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create quiz: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->courseItem->name = $request->input('name');
        $quiz->courseItem->description = $request->input('description');
        $quiz->start = $request->input('start');
        $quiz->finish = $request->input('finish');
        $quiz->duration = $request->input('duration');
        $quiz->save();

        return redirect()->route('quiz.show', ['id' => $id]);
    }

    public function destroy(string $id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('course.show.edit', ['id' => $quiz->courseItem->course_section_id]);
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

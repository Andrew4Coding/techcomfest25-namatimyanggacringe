<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function showQuiz()
    {
        return view('quiz.quiz');
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

        dd($data);

        // Close the file
        fclose($fileHandle);

        // Convert the array to JSON
        $json = json_encode($data, JSON_PRETTY_PRINT);

        dd($json);

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
            dd($text);
        } catch (\Exception $e) {
            dd($e);
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
            $newQuizItem->id  = (string) Str::uuid();
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
        }
        catch (\Exception $e) {
            dd($e);
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
}
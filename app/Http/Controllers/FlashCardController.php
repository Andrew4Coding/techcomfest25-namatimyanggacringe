<?php

namespace App\Http\Controllers;

use App\Models\FlashCard;
use App\Models\FlashCardItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\PdfToText\Pdf;

class FlashCardController extends Controller
{
    public function index()
    {
        $flashcards = FlashCard::all();
        return view('flashcard.index', compact('flashcards'));
    }

    public function create(Request $request)
    {
        
        try {
            $request->validate([
                'name' => 'required',
                'pdf' => 'required|mimes:pdf|max:2048', // Validate file type and size
            ]);
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

                $response = OpenAI::chat()->create([
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a helpful assistant.',
                        ],
                        [
                            'role' => 'user',
                            'content' => "
                                    Saya memiliki sebuah teks materi pelajaran SMA yang panjang. Saya ingin Anda menganalisis teks tersebut dan membuat daftar 20 pertanyaan beserta jawabannya dalam format JSON. 
                                    Berikut format JSON yang saya inginkan:
                                    [
                                    {
                                        \"pertanyaan\": \"Ini pertanyaannya\",
                                        \"jawaban\": \"Ini jawaban\"
                                    },
                                    ...
                                    ]

                                    Instruksi:
                                    1. Pertanyaan dan jawaban harus relevan dengan isi teks yang saya berikan.
                                    2. Gunakan bahasa Indonesia yang baku dan mudah dipahami oleh siswa SMA.
                                    3. Buatlah pertanyaan dalam berbagai jenis (pilihan ganda, esai pendek, atau benar/salah), tetapi cukup fokus pada esai pendek.
                                    4. Jawaban harus padat, jelas, dan langsung menjawab pertanyaan.
                                    5. Prioritaskan pembuatan pertanyaan yang menguji pemahaman konsep, fakta penting, atau analisis teks.
                                    6. Output berbentuk pure json dan tidak ada basi-basi. Tidak ada kata 'berikut ini' bla bla bla

                                    Berikut teks materi pelajaran yang perlu dianalisis:
                                    $text

                                    Buatkan daftar 20 soal dan jawaban berdasarkan teks di atas.               
                            ",
                        ],
                    ],
                ]);

                $message = $response->choices[0]->message->content ?? 'Sorry, no response.';
                $message = str_replace('json', '', $message);

                // Replace ``` with empty string
                $message = str_replace('```', '', $message);


                if (json_validate($message) === false) {
                    return back()->withErrors(['error' => 'Unable to process the PDF: ' . $message]);
                }

                // Parse message into json
                $json = json_decode($message, true);

                // Make new Flashcard object
                $newFlashcard = new FlashCard();
                $newFlashcard->name = $request->name;
                $newFlashcard->description = $request->description;
                $newFlashcard->subject = $request->subject;
                $newFlashcard->user_id = $request->user()->id;

                // Save the new Flashcard object
                $newFlashcard->save();

                // Generate FlashCardItems from the JSON
                foreach ($json as $item) {
                    $newFlashcardItem = new FlashCardItem();
                    $newFlashcardItem->question = $item['pertanyaan'];
                    $newFlashcardItem->answer = $item['jawaban'];
                    $newFlashcardItem->flash_card_id = $newFlashcard->id;
                    $newFlashcardItem->save();
                }

                // Get all flashcards related to current user
                $flashcards = FlashCard::where('user_id', $request->user()->id)->get();

                return view('flashcard.index', compact('flashcards'));
            } else {
                return back()->withErrors(['error' => 'No file uploaded.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to process the PDF: ' . $e->getMessage()]);
        }

    }

    public function show(string $id) {
        $flashcard = FlashCard::find($id);

        // Get all FlashCardItems related to the FlashCard
        $flashcardItems = FlashCardItem::where('flash_card_id', $flashcard->id)->get();
        
        return view('flashcard.show', compact('flashcard', 'flashcardItems'));
    }

    public function delete(string $id) {
        $flashcard = FlashCard::find($id);

        // Delete all FlashCardItems related to the FlashCard
        $flashcardItems = FlashCardItem::where('flash_card_id', $flashcard->id)->get();
        foreach ($flashcardItems as $flashcardItem) {
            $flashcardItem->delete();
        }

        // Delete the FlashCard
        $flashcard->delete();

        return redirect()->route('flashcard.index');
    }

    public function update(Request $request, string $id) {
        $flashcard = FlashCard::find($id);
        $flashcard->name = $request->name;
        $flashcard->description = $request->description;
        $flashcard->subject = $request->subject;
        $flashcard->save();

        return redirect()->back();
    }
}

<?php

namespace App\Jobs;

use App\Enums\QuestionType;
use App\Models\Quiz;
use App\Models\QuizSubmission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class CheckSubmission implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $PROMPT = 'Saya akan memberikan dua jawaban esai. Jawaban pertama adalah jawaban yang diberikan oleh guru sebagai referensi, dan jawaban kedua adalah jawaban yang diberikan oleh murid. Tugas Anda adalah untuk membandingkan kedua jawaban ini, memberikan nilai untuk masing-masing berdasarkan kualitas jawaban, serta memberikan umpan balik yang jelas dan objektif. Meskipun jawaban mahasiswa belum sempurna, jika sudah mencakup poin-poin penting, berikan nilai yang sesuai dengan usaha yang ditunjukkan.

*Format input:*
- *Pertanyaan:* Pertanyaan utama yang ditanyakan pada soal.
- *GURU:* Jawaban dari guru.
- *MURID:* Jawaban dari murid.
- *BOBOT:* Bobot nilai yang diberikan pada soal (skala dari 0 sampai maksimal).

*Format output (dalam format JSON):*
1. *nilai:* Nilai dari 0 sampai dengan bobot yang diberikan untuk jawaban murid, berdasarkan kualitas jawaban. Berikan nilai yang lebih rendah jika jawaban kurang mencakup banyak poin penting, tetapi tetap objektif dan adil.
2. *feedback:* Umpan balik yang memberikan saran konstruktif dengan cara yang tegas namun tetap membangun. Fokus pada aspek yang perlu diperbaiki, dengan memberikan penjelasan yang jelas tentang apa yang kurang.

---

*Contoh Input dan Output:*

*Input:*
- *Pertanyaan:* Apa yang dimaksud dengan hukum pidana?
- *GURU:* "Hukum pidana adalah hukum yang mengatur tentang tindak pidana dan akibat hukum dari tindakan tersebut. Sebagai contoh, pencurian diatur dalam Kitab Undang-Undang Hukum Pidana (KUHP)."
- *MURID:* "Hukum pidana itu untuk mengatur tindakan kriminal seperti mencuri."
- *BOBOT:* 10

*Output:*
json
{
  "nilai": 6,
  "feedback": "Jawaban Anda sudah menyebutkan inti dari hukum pidana, namun masih terlalu umum. Penjelasan Anda tentang akibat hukum atau contoh yang lebih konkret akan meningkatkan kualitas jawaban Anda. Cobalah untuk menjelaskan lebih rinci agar jawaban Anda lebih lengkap."
}


---

*Input:*
- *Pertanyaan:* Apa yang dimaksud dengan konstitusi negara?
- *GURU:* "Konstitusi adalah hukum dasar negara yang menetapkan prinsip-prinsip dasar dalam suatu negara. Misalnya, UUD 1945 di Indonesia mengatur tentang hak-hak asasi manusia dan pembagian kekuasaan antara eksekutif, legislatif, dan yudikatif."
- *MURID:* "Konstitusi itu adalah hukum tertinggi yang mengatur negara."
- *BOBOT:* 8

*Output:*
json
{
  "nilai": 5,
  "feedback": "Jawaban Anda sudah menyebutkan dasar definisi konstitusi, tetapi kurang mendalam. Anda bisa menambahkan contoh seperti UUD 1945 atau menjelaskan lebih lanjut tentang bagaimana konstitusi mengatur pembagian kekuasaan di negara. Perbaikan ini akan membuat jawaban Anda lebih solid."
}

---

*Input:*
- *Pertanyaan:* %s
- *GURU:* "%s"
- *MURID:* "%s"
- *BOBOT:* %d

*Output:*
';

    protected $quizId;
    protected $studentId;
    protected $submission;
    protected array $studentAnswers = [];

    /**
     * Create a new job instance.
     */
    public function __construct(string $quizId, $studentId)
    {
        try {
            $this->quizId = $quizId;
            $this->studentId = $studentId;

            // Fetch the submission and its associated items
            $this->submission = QuizSubmission::with('quizSubmissionItems')
                ->where('quiz_id', $quizId)
                ->where('student_id', $studentId)
                ->firstOrFail();

            // Map submission items (answers) to question IDs
            foreach ($this->submission->quizSubmissionItems as $item) {
                $this->studentAnswers[$item->question_id] = $item;
            }

            // Log submission details (instead of using echo)
            Log::info('Submission data loaded for student: ' . $studentId, ['submission' => $this->submission]);

        } catch (\Exception $exception) {
            Log::error('Error loading submission: ' . $exception->getMessage());
            $this->fail($exception->getMessage());
        }

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Exit early if submission is already marked as done
            if (!$this->submission->done) {
                return;
            }

            // Fetch quiz details, including associated questions
            $quiz = Quiz::with('questions')->find($this->quizId);

            // Loop through each question in the quiz and check answers
            foreach ($quiz->questions as $question) {
                // Ensure we have a corresponding answer for the question
                if (!isset($this->studentAnswers[$question->id])) {
                    continue; // Skip if no answer is found for the question
                }

                $curItem = $this->studentAnswers[$question->id];

                switch ($question->question_type) {
                    case QuestionType::ShortAnswer:
                    case QuestionType::MultipleChoice:
                        if ($curItem->answer === $question->answer) {
                            $curItem->score = $question->weight;
                        }
                        break;

                    case QuestionType::MultiSelect:
                        $studentAnswers = explode(',', $curItem->answer);
                        $correctAnswers = explode(',', $question->answer);
                        sort($studentAnswers);
                        sort($correctAnswers);
                        if ($studentAnswers === $correctAnswers) {
                            $curItem->score = $question->weight;
                        }
                        break;

                    case QuestionType::Essay:
                        $completion = OpenAI::chat()->create([
                            'model' => 'gpt-4o-mini',
                            'messages' => [
                                ['role' => 'user',
                                'content' => sprintf(
                                    $this->PROMPT,
                                    $question->content,
                                    $question->weight,
                                    $question->answer,
                                    $curItem->answer,
                                )]],
                        ])->choices[0]->message->content;

                        $completion = trim($completion, '`');
                        $completion = trim($completion);
                        $completion = substr($completion, 4);
                        $completion = trim($completion);

                        if (json_validate($completion)) {
                            $jsonAns = json_decode($completion, true);

                            if (isset($jsonAns->nilai)) {
                                $curItem->score = $jsonAns->nilai;
                            } else {
                                $curItem->score = 0;
                            }

                            if (isset($jsonAns->feedback)) {
                                $curItem->feedback = 'Feedback by Mindora AI:\n' . $jsonAns->feedback;
                            }
                        }
                        break;

                    default:
                        break;
                }

                // Save the updated item (this is the correct usage)
                $curItem->save();
            }
        } catch (\Exception $exception) {
            Log::error('Error processing submission: ' . $exception->getMessage());
            $this->fail($exception->getMessage());
        }
    }
}

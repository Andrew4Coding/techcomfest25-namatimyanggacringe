<?php

namespace App\Jobs;

use App\Enums\QuestionType;
use App\Models\QuestionChoice;
use App\Models\Quiz;
use App\Models\QuizSubmission;
use App\Models\QuizSubmissionItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class CheckSubmission implements ShouldQueue
{
use Queueable, SerializesModels;

private $PROMPT = 'Saya akan memberikan data soal dan jawaban siswa dalam format berikut:

```
Nomor: <NOMOR>
Jenis Soal: <JENIS_SOAL>
Soal: <SOAL>
Jawaban: <JAWABAN>
Jawaban Murid: <JAWABAN_MURID>
Bobot: <BOBOT>
```

**Tugas Anda:**
Memberikan penilaian dan feedback dalam bentuk array of objects dalam format JSON. Setiap objek harus memuat:

1. **nilai**: Angka dalam rentang 0 hingga `<BOBOT>`, ditentukan berdasarkan relevansi dan kebenaran jawaban murid.
2. **feedback**: Komentar spesifik terkait jawaban murid, yang mencakup hal-hal berikut:
   - Apa yang sudah benar.
   - Apa yang perlu diperbaiki.
   - Saran untuk meningkatkan pemahaman.

**Catatan penting:**
- **Jenis soal dan cara penilaian:**
  - **Pilihan Ganda - Multiple Choice**: Nilai penuh jika jawaban murid benar, nilai 0 jika salah.
  - **Isian Singkat**: Periksa kesesuaian kata kunci atau frasa penting dalam jawaban.
  - **Pilihan Ganda - Multiselect**: Berikan nilai parsial jika beberapa opsi benar, nilai 0 jika semua salah.
  - **Esai**: Nilai berdasarkan kesesuaian isi, struktur, dan kelengkapan jawaban dibandingkan dengan jawaban benar.

Berikan hasil seperti ini:
```json
[
  {
    "nilai": "<NILAI>",
    "feedback": "<FEEDBACK>"
  }
]
```

**Contoh Input:**
```
Nomor: 1
Jenis Soal: Pilihan Ganda - Multiple Choice
Soal: Apa ibu kota Indonesia?
Jawaban: Jakarta
Jawaban Murid: Jakarta
Bobot: 5

Nomor: 2
Jenis Soal: Isian Singkat
Soal: Sebutkan salah satu negara di Asia Tenggara.
Jawaban: Indonesia, Malaysia, Singapura, dll.
Jawaban Murid: Malaysia
Bobot: 10

Nomor: 3
Jenis Soal: Esai
Soal: Jelaskan dampak revolusi industri terhadap perekonomian dunia.
Jawaban: Revolusi industri meningkatkan efisiensi produksi, membuka lapangan kerja, dan memperluas perdagangan internasional.
Jawaban Murid: Revolusi industri membuka banyak lapangan kerja baru, tetapi juga menyebabkan beberapa orang kehilangan pekerjaan karena otomatisasi.
Bobot: 20

Nomor: 4
Jenis Soal: Pilihan Ganda - Multi Select
Soal: Tentukan mana saja elemen di bawah ini yang merupakan golongan Gas Mulia!
Jawaban: Helium,Neon,Argon
Jawaban Murid: Helium,Argon,Lithium
Bobot: 9
```

**Contoh Output:**
```json
[
  {
    "nilai": 5,
    "feedback": "Jawaban benar. Anda sudah memahami materi dengan baik."
  },
  {
    "nilai": 10,
    "feedback": "Jawaban tepat. Anda telah menyebutkan salah satu negara di Asia Tenggara."
  },
  {
    "nilai": 15,
    "feedback": "Jawaban Anda cukup baik karena menjelaskan dampak revolusi industri terhadap lapangan kerja. Namun, Anda bisa menambahkan poin tentang efisiensi produksi dan perdagangan internasional untuk jawaban yang lebih lengkap."
  },
  {
    "nilai": 6,
    "feedback": "Jawaban Anda kurang tepat, Lithium bukan merupakan golongan Gas Mulia, melainkan termasuk golongan Logam Alkali. Anda juga belum menyebutkan salah satu logam mulia yang juga terdapat pada pilihan, yaitu Neon."
  }
]
```

Berikut merupakan INPUT:


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
//        try {
        // Exit early if submission is already marked as done
        if (!$this->submission->done) {
            return;
        }

        $prompt = $this->PROMPT;

        $JENIS = array(
            'multiple_choice' => 'Pilihan Ganda - Multiple Choice',
            'short_answer' => 'Isian Singkat',
            'essay' => 'Esai',
            'multi_select' => 'Pilihan Ganda - Multi Select',
        );

        foreach ($this->submission->quizSubmissionItems as $i => $item) {
            $prompt = $prompt . 'No: ' . $i . '\n';
            $prompt = $prompt . 'Jenis Soal: ' . $JENIS[$item->question->question_type->value] . '\n';
            $prompt = $prompt . 'Soal: ' . $item->question->content . '\n';

            if ($item->question_type == QuestionType::MultipleChoice || $item->question_type == QuestionType::MultiSelect) {
                $questionAnswers = explode(",", rtrim($item->question->answer, ','));
                $answers = explode(",", rtrim($item->answer, ','));

                $stringifiedQuestionAnswers = '';
                $stringifiedAnswers = '';

                for ($i = 0; $i < count($questionAnswers); $i++) {
                    $qAns = QuestionChoice::where('id', $questionAnswers[$i])->first();
                    $ans = QuestionChoice::where('id', $answers[$i])->first();

                    $stringifiedQuestionAnswers .= $qAns->content . ',';
                    $stringifiedAnswers .= $ans->content . ',';
                }

                $prompt .= 'Jawaban: ' . rtrim($stringifiedQuestionAnswers . ',') . '\n';
                $prompt .= 'Jawaban Murid: ' . rtrim($stringifiedAnswers . ',') . '\n';
            } else {
                $prompt = $prompt . 'Jawaban: ' . $item->question->answer . '\n';
                $prompt = $prompt . 'Jawaban Murid: ' . $item->answer . '\n';
            }

            $prompt = $prompt . 'Bobot: ' . $item->question->weight . '\n\n';
        }

        $coba = 3;

        while ($coba > 0) {
            $coba--;

            $msg = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ]
            ])->choices[0]->message->content;

            $msg = trim($msg, '`');
            $msg = trim($msg);
            $msg = substr($msg, 4);
            $msg = trim($msg);

            if (json_validate($msg)) {
                $jsonAns = json_decode($msg, true);

                foreach ($this->submission->quizSubmissionItems as $i => $item) {
                    $item->score = $jsonAns[$i]['nilai'];
                    $item->feedback = $jsonAns[$i]['feedback'] . "\n(Feedback ini digenerate oleh AI)";
                    $item->save();
                }

                break;
            }
        }

//        } catch (\Exception $exception) {
//            Log::error('Error processing submission: ' . $exception->getMessage());
//            $this->fail($exception->getMessage());
//        }
    }
}

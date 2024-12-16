<?php

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Livewire\Quiz\Essay;
use App\Models\Course;
use App\Models\CourseItem;
use App\Models\CourseSection;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Quiz;
use App\Models\Teacher;
use Illuminate\Console\View\Components\Choice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $quiz = Quiz::factory()->createOne(['id' => '9db9b43e-db7a-4230-a9ae-f73f8e872ad8', 'finish' => date_add(date_create(), \DateInterval::createFromDateString('2 hours'))]);

//        $question = Question::factory()->count(10)->create([], $quiz)->each(function (Question $question) {
//            $question->questionChoices()->saveMany(QuestionChoice::factory()->count(5)->make([], $question));
//        });
        $question = Question::factory()->createOne([
            'quiz_id' => $quiz->id,
            'content' => 'Jelaskan apa yang dimaksud dengan sistem hukum dalam konteks negara Indonesia!',
            'answer' => 'Sistem hukum Indonesia adalah keseluruhan peraturan dan norma yang berlaku di Indonesia, yang mengatur hubungan antara individu dengan negara, antarindividu, serta individu dengan masyarakat. Sistem ini mencakup berbagai jenis peraturan, seperti hukum pidana, hukum perdata, hukum administrasi negara, dan hukum tata negara, yang diatur berdasarkan ideologi Pancasila dan Undang-Undang Dasar 1945. Selain itu, sistem hukum Indonesia juga dipengaruhi oleh sistem hukum yang berlaku di berbagai negara, seperti hukum Belanda dan hukum adat.',
            'weight' => 10,
            'question_type' => QuestionType::Essay,
        ]);

        $question2 = Question::factory(9)->create()->each(function (Question $question) {
            $choices = QuestionChoice::factory(3)->make()->each(function (QuestionChoice $choice) use ($question) {
                $choice->question_id = $question->id;
                $choice->save();
            });
        });

        $teacher = Teacher::factory()->createOne();
        $course = Course::factory()->createOne([
            'teacher_id' => $teacher->id,
        ]);
        $courseSection = CourseSection::factory()->createOne([
            'course_id' => $course->id,
        ]);

        $quiz->questions()->save($question);
        $quiz->questions()->saveMany($question2);
        $quiz->courseItem()->create([
            'name' => "Kuis 1",
            'description' => "Kuis 1",
            'course_section_id' => $courseSection->id,
        ]);
    }
}

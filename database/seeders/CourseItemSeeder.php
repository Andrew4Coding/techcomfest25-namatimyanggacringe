<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\CourseSection;
use App\Models\Forum;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course_sections = CourseSection::all();
        $teacher = User::where('email', 'b@co')->first();

        // Create new material, submission, and attendance for each course section
        $course_sections->each(function (CourseSection $course_section) use ($teacher) {
            $this->createMaterial($course_section->id, "pdf", "https://techcomfest.s3.ap-southeast-2.amazonaws.com/pdfs/675988ca58bfd.pdf", now());
            $this->createSubmission($course_section->id, "This is a dummy submission", now(), now()->addDays(7), "pdf,docx", 3, now()->addDays(1));
            $this->createAttendance($course_section->id, "dummy123", now()->addDays(7), now()->addDays(2));
            $this->createForum($course_section->id, $teacher->id, now()->addDays(3));
        });
    }

    public function createMaterial($course_section_id, $material_type, $file_url, $created_at) {
        $material = new Material();
        $material->file_url = $file_url;
        $material->material_type = $material_type;
        $material->save();

        $material->courseItem()->create([
            'name' => "Material Dummy",
            'description' => "This is a dummy material",
            'course_section_id' => $course_section_id,
            'created_at' => $created_at,
        ]);
    }

    public function createSubmission($course_section_id, $content, $opened_at, $due_date, $file_types, $max_attempts, $created_at) {
        $submission = new Submission();
        $submission->content = $content;
        $submission->opened_at = $opened_at;
        $submission->due_date = $due_date;
        $submission->file_types = $file_types;
        $submission->max_attempts = $max_attempts;
        $submission->save();

        $submission->courseItem()->create([
            'name' => "Submission Dummy",
            'description' => "This is a dummy submission",
            'course_section_id' => $course_section_id,
            'created_at' => $created_at,
        ]);
    }

    public function createAttendance($course_section_id, $password, $valid_until, $created_at) {
        $attendance = new Attendance();
        $attendance->password = $password;
        $attendance->valid_until = $valid_until;
        $attendance->save();

        $attendance->courseItem()->create([
            'name' => "Attendance Dummy",
            'description' => "This is a dummy attendance",
            'course_section_id' => $course_section_id,
            'created_at' => $created_at,
        ]);
    }

    public function createForum($course_section_id, $creator_id, $created_at) {
        $forum = new Forum();
        $forum->creator_id = $creator_id;
        $forum->save();

        $forum->courseItem()->create([
            'name' => "Forum Dummy",
            'description' => "This is a dummy forum",
            'course_section_id' => $course_section_id,
            'created_at' => $created_at,
        ]);
    }
}

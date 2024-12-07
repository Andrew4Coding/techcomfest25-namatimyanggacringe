<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(string $courseId)
    {
        return view('forum.index');
    }

    public function show($id)
    {
        return view('forum.show', ['id' => $id]);
    }
}

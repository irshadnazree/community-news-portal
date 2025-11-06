<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EditorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:editor,admin']);
    }

    public function reviewQueue()
    {
        return view('editor.review-queue');
    }
}

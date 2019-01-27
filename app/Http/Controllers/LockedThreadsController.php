<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread) {

        $thread->update(['locked' => 1]);

    }

    public function destroy(Thread $thread)
    {

        $thread->update(['locked' => 0]);
    	
    }
}

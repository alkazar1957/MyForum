<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Trending;

class SearchController extends Controller
{
    
    public function index(Trending $trending)
    {
    	$search = request('q');

    	$threads = Thread::search($search)->paginate(25);

    	if (request()->expectsJson()) {
    		return $threads;
    	}

    	return view('threads.index', [
    		'threads' => $threads,
    		'trending' => $trending->get()
    	]);
    }
}

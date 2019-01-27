<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use App\Trending;

use App\Filters\ThreadFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
use App\Rules\Recaptcha;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleWare('auth')->except([ 'index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        // $trending = array_map('json_decode',Redis::zrevrange('trending_threads', 0, 4));

        if (request()->wantsJson())
        {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Thread $thread
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Thread $thread, Recaptcha $recaptcha)
    {
        // dd(request()->all());
        // post https://www.google.com/recaptcha/api/siteverify
        $validated = request()->validate([
                'title'         => 'required|spamfree',
                'body'          => 'required|spamfree',
                'channel_id'    => 'required|exists:channels,id',
                'g-recaptcha-response' => ['required', $recaptcha]
            ]);

        $validated['user_id']   = auth()->id();

        $thread = $thread->addThread($validated);

        if (request()->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path())
            ->with('flash', 'Your Thread has been published');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Channel  $channelId
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        // $key = sprintf("users.&s.visits.&s", auth()->id(), $thread->id);

        // cache()->forever($key, Carbon::now());

        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $thread->increment('views');

        // $thread->visits()->record();
// dd($thread->visits()->count());
        $trending->push($thread);

        // Redis::zincrby('trending_threads', 1, json_encode([
        //     'title' => $thread->title,
        //     'path' => $thread->path()
        // ]));

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        
        $validated = request()->validate([
                'title'         => 'required|spamfree',
                'body'          => 'required|spamfree',
                // 'channel_id'    => 'required|exists:channels,id',
                // 'g-recaptcha-response' => ['required', $recaptcha]
            ]);

        $thread->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        // if($thread->user_id != auth()->id()) {
        //     abort(403, 'You Do Not Have Permission To Do This.');
        //     return redirect('/login');
        // }

        return redirect('/threads');
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Channel       $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        // dd($filters);
        return $threads->paginate(25);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Forms\CreatePostForm;
use App\Notifications\YouWereMentioned;

use App\User;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleWare('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(15);
        dd('RC index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('RC create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Channel           $channelId
     * @param Thread            $thread
     * @param CreatePostForm    $form
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        // return $form->persist($thread);
        if ($thread->locked) {
            return response('Thread is locked.', 422);
        }

       return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        dd('RC show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        dd('RC edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
       $this->authorize('update', $reply);
       request()->validate(['body' => 'required|spamfree']);
       $reply->update(['body' => request('body')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        if(request()->expectsJson()) {
            $reply->delete($reply->id);
            return response(['status' => 'Reply deleted']);
        }
        $reply->delete($reply->id);
        return back();
    }
}
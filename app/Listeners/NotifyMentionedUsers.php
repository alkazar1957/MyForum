<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\YouWereMentioned;

use App\User;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
       // preg_match_all('/ \@([^\s\.]+)/i', $event->reply->body, $matches);
       // $mentionedUser = $event->reply->mentionedUsers();
// dd($event->reply->mentionedUsers());
       $users = User::whereIn('username', $event->reply->mentionedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });

       // dd($users);
       // collect($event->reply->mentionedUsers())
       //      ->map(function ($name) {
       //          return User::whereName($name)->first();
       //      })
       //      ->filter()
       //      ->each(function ($user) use ($event) {
       //          $user->notify(new YouWereMentioned($event->reply));
       //      });

       // foreach ( $mentionedUsers as $name ) {
       //      $user = User::whereName($name)->first();

       //      if ( $user )
       //      {
       //          $user->notify(new YouWereMentioned($event->reply));
       //      }
       // }


    }
}

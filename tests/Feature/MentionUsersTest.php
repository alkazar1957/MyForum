<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);
        $this->signIn($john);
        $jane = create('App\User', ['name' => 'JaneDoe']);

        $thread = createThread();

        $reply = make('App\Reply' , [
        	'thread_id' => $thread->id,
        	'body' => 'a @JaneDoe look at this and @JohnDoe or @markdoe'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    function it_can_fetch_all_users_starting_with_the_given_characters ()
    {
        $user = create('App\User', ['username' => 'JohnDoe']);
        $user = create('App\User', ['username' => 'JaneDoe']);
        $user = create('App\User', ['username' => 'JohnDoe2']);
        $results = $this->json('get', '/api/users', ['username' => 'Joh']);

        $this->assertCount(2, $results->json());
    }

}

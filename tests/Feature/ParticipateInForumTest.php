<?php

namespace Tests\Feature;

use tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp ()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
        $this->reply  = factory('App\Reply')->create();
    }

    /** @test */
    function unauthenticaed_users_may_not_add_replies ()
    {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function  an_authenticated_user_can_participate_in_forum_threads ()
    {
        $this->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->be($user = factory('App\User')->create());

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorised_users_cannot_delete_a_reply ()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->signIn()->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    function authorised_users_can_delete_a_reply ()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);
// dd(auth()->id(),$reply->user_id);
        
        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    function authorised_users_can_update_a_reply ()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = 'Body has been updated';
        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);

    }

    /** @test */
    function unauthorised_users_cannot_update_a_reply ()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('/login');
        $this->signIn()->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    function replies_that_contain_spam_may_not_be_created ()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = createThread();
        $reply = make('App\Reply', [
            'body' => 'Yahoo customer support'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    function a_user_may_only_reply_once_per_minute ()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = createThread();

        $reply = make('App\Reply', [
            'body' => 'A reply to a thread'
        ]);
        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(201);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);
    }
}

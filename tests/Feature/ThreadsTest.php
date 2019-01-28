<?php

namespace Tests\Feature;

use tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp ()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    function a_thread_has_a_path ()
    {
        $thread = createThread();
        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path()
        ); 
    }
    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->signIn();
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        $this->signIn();
        $this->get('/threads/some-channel/' . $this->thread->slug)
            ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/'. $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->withExceptionHandling()->be($user = factory('App\User')->create());

        $threadByUser = create('App\Thread', ['user_id' => $user->id]);
        $threadNotByUser = create('App\Thread');

        $this->get('/threads?by='.$user->username)
            ->assertSee($threadByUser->title)
            ->assertDontSee($threadNotByUser->title);
    }
    /** @test */
    function a_user_can_filter_threads_by_popularity ()
    {
        $this->signIn();
        $threadWithTwoReplies = create('App\Thread');

        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
        $threadWithThreeReplies = create('App\Thread');

        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);
        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_threads_which_have_no_replies ()
    {
        $this->signIn();
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);
        $response = $this->getJson('/threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_thread ()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);

        // dd($response); 
    }

}

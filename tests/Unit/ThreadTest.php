<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

class ThreadTest extends TestCase
{
	use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {

        parent::setUp();

        $this->thread = create('App\Thread');
    }
	// /** @test */
    function a_thread_has_replies ()
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('Illuminate\Database\Elloquent\Collection', $thread->replies);
    }

	/** @test */
    public function a_thread_has_a_creator()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('App\User', $thread->creator);
    }

    /**  @test */
    function a_thread_can_add_a_reply ()
    {
        $thread = factory('App\Thread')->create();

        $thread->addReply([
        	'body' => 'foobar',
        	'user_id' => 1,
        ]);

        $this->assertCount(1, $thread->replies);
    }

    /** @test */
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added ()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
            'body' => 'foobar',
            'user_id' => 999
        ]);

        Notification::assertSentTo(auth()->user(), \App\Notifications\ThreadWasUpdated::class);
    }

    /** @test */
    function a_thread_can_make_a_string_path()
    {
        $thread = factory('App\Thread')->create();
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }
    /** @test */
    function a_thread_belongs_to_a_channel()
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to ()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from ()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);
        
        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies ()
    {
        $this->signIn();
        $thread = create('App\Thread');
        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));
            $user->read($thread);
            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    function a_thread_records_each_visit ()
    {
        $thread = createThread();

        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());

        // $thread->visits();

        // $thread->recordVisits();

        // $this->assertEquals(2, $thread->visits());

    }

    /** @test */
    function record_a_new_visit_in_the_database_for_each_new_visit ()
    {
        $thread = createThread();

        $this->assertSame(0, $thread->views);

        $this->call('get', $thread->path());

        $this->assertEquals(1, $thread->fresh()->views);
    }

    /** @test */
    function a_threads_body_is_sanitized_automatically ()
    {
        $thread = make('App\Thread', ['body' => '<script>alert("bad bad bad")</script> but this is ok']);

        $this->assertEquals(' but this is ok', $thread->body);

        // dd($thread->body);
    }

}

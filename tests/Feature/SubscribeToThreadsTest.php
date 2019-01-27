<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function a_user_can_subscribe_to_threads ()
	{
		$thread = createThread();
		$this->signIn();

		$this->post($thread->path().'/subscriptions/');

		$this->assertCount(1, $thread->fresh()->subscriptions);

	}

	/** @test */
	function it_knows_if_the_authenticated_user_is_subscribed_to_it ()
	{
		$thread = createThread();
		$this->signIn();

		$this->assertFalse($thread->isSubscribedTo);

		$thread->subscribe();

		$this->assertTrue($thread->isSubscribedTo); 
	}

	/** @test */
	function a_user_can_unsubscribe_from_a_thread ()
	{
		$thread = createThread();
		$this->signIn();
		$this->assertFalse($thread->isSubscribedTo);

		$thread->subscribe();

		$this->delete($thread->path() . '/subscriptions');

		$this->assertCount(0, $thread->subscriptions);
	}

}
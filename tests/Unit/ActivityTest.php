<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

	/** @test */
	function it_records_activity_when_a_user_creates_a_thread ()
	{
		$this->signIn();

		$thread = create('App\Thread');

		$this->assertDatabaseHas('activities', [
			'user_id'		=> auth()->id(),
			'type' 			=> 'created_thread',
			'subject_id' 	=> $thread->id,
			'subject_type' 	=> 'App\Thread'
		]);

		$activity = \App\Activity::first();

		$this->assertEquals($activity->subject->id, $thread->id);
	}

	/** @test */
	function it_records_activity_when_a_reply_is_created ()
	{
		$this->signIn();

		$reply = create('App\Reply');

		$this->assertEquals(2, \App\Activity::count());
		
	}

	/** @test */
	function it_fetches_a_feed_for_any_user ()
	{
		$this->signIn();
		create('App\Thread', ['user_id' => auth()->id()], 2);

		auth()->user()->activity()->first()->update(['created_at' => \Carbon\Carbon::now()->subWeek()]);
		$feed = \App\Activity::feed(auth()->user());

		$this->assertTrue($feed->keys()->contains(
			\Carbon\Carbon::now()->format('Y-m-d')
		));

		$this->assertTrue($feed->keys()->contains(
			\Carbon\Carbon::now()->subWeek()->format('Y-m-d')
		));
	}

}
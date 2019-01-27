<?php

namespace Tests\Feature;

use tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

	/** @test */
	function a_use_has_a_profile ()
	{
		// $user = create('App\User');
		$this->signIn();

		$this->get('/profiles/' . auth()->user()->name)
			->assertSee(auth()->user()->name);
	}
	/** @test */
		function profiles_show_all_threads_created_by_a_user ()
		{
			$this->signIn();
			$thread = create('App\Thread', ['user_id' => auth()->id()]);

			$this->get('/profiles/' . auth()->user()->name)
				->assertSee($thread->title);
		}	
}
<?php

namespace Tests\Feature;

use tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouritesTest extends TestCase
{
    use DatabaseMigrations;

	/** @test */
	function guests_cannot_favourite_a_reply ()
	{
    	$reply = create('App\Reply');
		
    	$this->withExceptionHandling()
    		->post('/replies/' . $reply->id . '/favourites')
    		->assertRedirect('/login');
	}

    /** @test */
    public function an_authenticated_user_can_favourite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('/replies/' . $reply->id . '/favourites');

        $this->assertCount(1, $reply->favourites);
    }

   /** @test */
    public function an_authenticated_user_can_unfavourite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $reply->favourite();

        $this->delete('/replies/' . $reply->id . '/favourites');

        $this->assertCount(0, $reply->favourites);
    }

    	/** @test */
    	function an_authenticated_user_can_only_favourite_a_reply_once ()
    	{
    	$this->signIn();
    	
    	$reply = create('App\Reply');

    	try {
	    	$this->post('/replies/' . $reply->id . '/favourites');
	    	$this->post('/replies/' . $reply->id . '/favourites');
	    } catch (\Exception $e) {
	    	$this->fail('Did not expect to insert the same record twice.');
	    }

    	$this->assertCount(1, $reply->favourites);
    		
    	}

}
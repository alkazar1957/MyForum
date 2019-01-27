<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

    public function setup()
    {
        parent::setup();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn('true');
            });
        });
    }

    /** @test  */
    public function guests_may_not_create_threads()
    {
    	$this->withExceptionHandling();

    	$this->get('/threads/create')
    		->assertRedirect('/login');

    	$this->post('/threads')
    		->assertRedirect('/login');
    }

    /** @test  */
    public function an_authenticated_user_can_create_threads()
    {
        // $this->withExceptionHandling();
        // $this->signIn();
        // $thread = createThread();

        // $response = $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $response = $this->publishThread(['title' => 'Some Title', 'body' => 'Some Body']);
        $this->get($response->headers->get('Location'))
        	->assertSee('Some Title')
        	->assertSee('Some Body');
    }

    /** @test */
    function a_thread_requires_a_title()
    {
    	$this->publishThread(['title' => null])
    		->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_unique_slug ()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'this is a title']);

        $this->assertEquals($thread->fresh()->slug , 'this-is-a-title');
   
        $thread = $this->postJson(route('threads.store', $thread->toArray() + ['g-recaptcha-response' => 'token']))->json();

        $this->assertEquals("this-is-a-title-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug ()
    {
        $this->signIn();
        $thread = create('App\Thread', ['title' => 'this is a title 12']);
        $thread = $this->postJson(route('threads.store', $thread->toArray() + ['g-recaptcha-response' => 'token']))->json();

        $this->assertEquals("this-is-a-title-12-{$thread['id']}", $thread['slug']);
    }
    /** @test */
    function a_thread_requires_a_body()
    {
    	$this->publishThread(['body' => null])
    		->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_recaptcha_verification ()
    {
        unset(app()[Recaptcha::class]);
        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
    	$channel = factory('App\Channel', 2)->create();
    	$this->publishThread(['channel_id' => null])
    		->assertSessionHasErrors('channel_id');
    	$this->publishThread(['channel_id' => '999999'])
    		->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function unauthorized_users_may_not_delete_threads ()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete( $thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete( $thread->path())->assertStatus(403);
    }

    /** @test */
    function unauthorized_users_may_not_update_threads ()
    {
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);
        $this->patchJson($thread->path(), [
            'title' => 'Changed',
            'body'  => 'Changed Body'
        ])->assertStatus(403);
    }

    /** @test */
    function a_thread_can_be_updated_by_its_creator ()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->patchJson($thread->path(), [
            'title' => 'Changed',
            'body'  => 'Changed Body'
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Changed', $thread->title);
            $this->assertEquals('Changed Body', $thread->body);
        });
    }

    /** @test */
    function authorized_users_can_delete_threads ()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $response->assertStatus(204);
        
        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $thread->id,
            'subject_type'  => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $reply->id,
            'subject_type'  => get_class($reply)
        ]);
    }

    public function publishThread($overrides = [])
    {
    	$this->withExceptionHandling()->be(factory('App\User')->create());
    	$thread = make('App\Thread', $overrides);
    	return $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
    }
}

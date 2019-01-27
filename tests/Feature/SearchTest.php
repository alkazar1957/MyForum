<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Thread;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_search_threads ()
    {
    	config(['scout.driver' => 'tntsearch']);
        $search = 'foo';
        create('App\Thread', [], 2);
        create('App\Thread', ['body' => "A body with the {$search} term"], 2);

        do {
	        $results = $this->getJson("/threads/search?q={$search}")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        Thread::latest()->take(4)->unsearchable();
    }
}

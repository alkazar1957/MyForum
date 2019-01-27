<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;

use App\Trending;

class TrendingThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected function setup() {
    	parent::setup();

    	$this->trending = new Trending();
    	$this->trending->reset();

        }
	/** @test */
	function it_increments_a_threads_score_each_time_it_is_read ()
	{
		$this->assertEmpty($this->trending->get());

		$thread = createThread();

		$this->call('get', $thread->path());
		$trending = $this->trending->get();

		$this->assertCount(1, $trending);

		$this->assertEquals($thread->title, $trending[0]->title);
	}    
}
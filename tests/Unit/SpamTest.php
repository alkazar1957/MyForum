<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Inspections\Spam;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

	/** @test */
	function it_validates_invalid_keywords ()
	{
		$spam = new Spam;

		$this->assertFalse($spam->detect('innocent message'));

		$this->expectException('Exception');

		$span->detect('Yahoo Customer Support');
	}

	/** @test */
	function it_checks_for_any_key_being_held_down ()
	{
		$spam = new Spam;

		$this->expectException('Exception');

		$spam->detect('Hello World aaaaaaaaaaaa');
	}

}
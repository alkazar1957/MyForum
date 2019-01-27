<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;
	/** @test */
	function  only_members_can_add_avatars()
	{
		$this->withExceptionHandling();
		$this->json('post', '/api/users/1/avatar')
			->assertStatus(401);
	}    

	/** @test */
	function a_valid_avatar_must_be_provided ()
	{
		$this->withExceptionHandling()->signIn();
		$this->json('post', '/api/users/'. auth()->id() .'/avatar', [
			'avatar' => 'not-an-image'
		])->assertStatus(422);
	}

	/** @test */
	function a_user_may_add_an_avatar_to_their_profile ()
	{
		$this->signIn();
		$storage = Storage::fake('public');
		$this->json('post', '/api/users/'. auth()->id() .'/avatar', [
			'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
		]);

		Storage::disk('public')->assertExists('avatars/' . $file->hashName());
	}


}
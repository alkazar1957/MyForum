<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use App\Helpers\Initials;

use App\User;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser'   => $user,
            'threads'       => $user->threads()->paginate(20),
            'activities'    => \App\Activity::feed($user)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profiles.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // dd($request->all());
        $user->body = $request->body;
        $user->update();
        return redirect('/profiles/'.$user->username);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function avatar(Request $request, User $user)
    {
        request()->validate([
            'avatar' => 'required|image'
        ]);
        $avatar = $request->file('avatar');
        $ext = $avatar->guessClientExtension();
        $avatar->storeAs('/users/'.auth()->id().'/avatars/', "avatar.{$ext}");

        $user->avatar = "avatar.{$ext}";
        $user->update();

        return response([], 204);

        // request()->validate([
        //     'avatar' => 'required|image'
        // ]);
        // $avatar = $request->file('avatar');
        // $ext = $avatar->guessClientExtension();
        // $avatar->storeAs('/users/'.auth()->id().'/avatars/', "avatar.{$ext}");

        // $user->avatar = "avatar.{$ext}";
        // $user->update();

        // return back(); 
    }

    public function showAvatar(User $user)
    {
        if($user->avatar == ''){
            // As the user does not have an avatar
            // get their initials from their name
            // to use as an image.
            // TODO:
            //      Add method for username initials image
            // $img = $user->getUsernameInitialsImage();
            $initial = new Initials;
            $initials = $initial->generate($user->username);

            // Create a new image resource
            $img = Image::canvas(800, 600, '#6cb2eb');

            // Define the defaults for the text image
            $img->text($initials, 160, 150, function($font) {
                $font->file(public_path().'/fonts/Bold-Italic.ttf');
                $font->size('400');
                $font->color('#000000');
                $font->align('left');
                $font->valign('top');
            });

            // send HTTP header and output image data
            return $img->orientate()->response('jpg', 70);

        } else {
            $storagePath = storage_path('app/users/' . $user->id . '/avatars/' . $user->avatar);
        }
        $img = Image::make($storagePath);
        return $img->orientate()->response();
    }


}

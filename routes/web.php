<?php
Route::get('/pretend', function (){

	$find = request()->query('user');

	if (null !== $find) {
		$user = \App\User::where('username', $find)->first();
	} else {
		abort(403);
		// $user = \App\User::findOrFail($find);
	}

	Auth::login($user);
	return redirect('/threads');
});

Route::get('/', function () {
    return view('welcome');
});

// Route::get('scan', function () {
// 	return view('scan');
// });
Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function(){
	Route::get('/home', 'HomeController@index')->name('home');

	Route::get 		('/threads', 					'ThreadsController@index'	)->name('threads');
	Route::POST 	('/threads', 					'ThreadsController@store'	)->name('threads.store');
	Route::get 		('/threads/create', 			'ThreadsController@create'	)->name('threads.create');
	Route::get 		('/threads/search', 			'SearchController@index' 	)->name('threads.search');
	Route::get 		('/threads/{channel}', 			'ThreadsController@index'	)->name('threads.index');
	Route::get 		('/threads/{channel}/{thread}', 'ThreadsController@show'	)->name('threads.show');
	Route::PATCH   	('/threads/{channel}/{thread}', 'ThreadsController@update'	)->name('threads.update');
	Route::DELETE  	('/threads/{channel}/{thread}',	'ThreadsController@destroy'	)->name('threads.destroy');

	Route::POST 	('/locked-threads/{thread}', 	'LockedThreadsController@store')->name('locked-threads.store')->middleware('isAdmin');
	Route::DELETE 	('/locked-threads/{thread}', 	'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('isAdmin');

	Route::get 		('/threads/{channel}/{thread}/replies', 'RepliesController@index'	)->name('replies.index');
	Route::POST 	('/threads/{channel}/{thread}/replies',	'RepliesController@store'	)->name('replies.store');
	Route::POST 	('/threads/{channel}/{thread}/subscriptions',	'SubscriptionsController@store'	)->name('subscriptions.store');
	Route::DELETE 	('/threads/{channel}/{thread}/subscriptions',	'SubscriptionsController@destroy'	)->name('subscriptions.destroy')->middleware('auth');

	Route::PATCH 	('/replies/{reply}', 					'RepliesController@update'	)->name('replies.update');
	Route::DELETE 	('/replies/{reply}',					'RepliesController@destroy'	)->name('replies.destroy');

	Route::POST 	('/replies/{reply}/favourites', 'FavouritesController@store')->name('replies.favourites');
	Route::DELETE 	('/replies/{reply}/favourites', 'FavouritesController@destroy')->name('replies.favourites');

	Route::POST 	('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

	Route::get 		('/profiles/edit',					'ProfilesController@edit')->name('profiles.edit'); 
	Route::get 		('/profiles/{user}', 				'ProfilesController@show')->name('profiles.show'); 
	Route::PATCH 	('/profiles/{user}/update',			'ProfilesController@update')->name('profiles.update'); 
	Route::post 	('/profiles/avatar/{user}',			'ProfilesController@avatar')->name('profiles.avatar');
	Route::get 		('/profiles/showAvatar/{user}',		'ProfilesController@showAvatar')->name('profiles.showAvatar');

	Route::DELETE	('/profiles/{user}/notifications/{notificationId}', 	'UserNotificationsController@destroy')->name('notifications.destroy'); 
	Route::get 		('/profiles/{user}/notifications',		 				'UserNotificationsController@index')->name('notifications.index'); 

});

	Route::get('/api/users', 'Api\UsersController@index');
	Route::POST('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth');
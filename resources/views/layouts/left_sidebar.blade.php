@if(isset($profileUser) || auth()->check())

<div class="card">

    <div class="card-header p-10pc">

        <img src="/profiles/showAvatar/{{ isset($profileUser) ? $profileUser->username : auth()->user()->username }}" width="150px" id="profile_picture" class="bg-info rounded-circle mb-2">

	    <div id="sidebarProfileUsername" class="card-text text-center ">
	    	{{ isset($profileUser) ? $profileUser->username : auth()->user()->username }}
	    </div>

    </div>

    <div id="sidebarProfile" class="card-body">
   		{!! isset($profileUser) ? $profileUser->body : auth()->user()->body !!}
   	</div>

</div>

@endif
        <nav class="navbar navbar-expand-md navbar-inverse navbar-fixed-top">
            <div class="container">
                <i class="fa fa-home"></i>
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                        <li>
                            <a class="nav-link" href="{{ route('threads.create') }}">
                            <i class="fa fa-sticky-note-o"></i>
                                New Thread
                            </a>
                        </li>
                        
                        <li class="nav-item dropdown">

                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                            aria-expanded="false">
                                <i class="fa fa-align-justify"></i>
                                Browse
                                 <span class="caret"></span>
                             </a>

                            <ul class="dropdown-menu">

                                <li><a class="dropdown-item" href="/threads">All Threads</a></li>

                                @if (auth()->check())

                                    <li><a class="dropdown-item" href="/threads?by={{ auth()->user()->username }}">My Threads</a></li>

                                @endif

                                <li><a class="dropdown-item" href="/threads?popular=1">Popular Threads</a></li>

                                <li><a  class="dropdown-item"href="/threads?unanswered=1">Unanswered Threads</a></li>

                            </ul>

                        </li>

                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                                Channels
                            </a>
                          <ul class="dropdown-menu">

                            @foreach($channels as $channel)
                                <li><a class="dropdown-item" href="/threads/{{$channel->slug}}">{{ $channel->name }}</a></li>
                            @endforeach
                          </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else

                            <form action="/threads/search" method="get" accept-charset="utf-8">
                                <li class="nav-item input-group">
                                        @csrf
                                        <input class="form-control navbar-input-search" type="text" name="q" placeholder="Search for Something..">
                                        <span class="input-group-btn mr-3">
                                            <button type="Submit" class="btn btn-xs btn-default pull-right fa fa-search"></button>
                                        </span>
                                </li>
                            </form>

                            <user-notifications></user-notifications>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-user-circle"></i>
                                    {{ Auth::user()->username }} 
                                    <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('/home')}}">
                                            <i class="fa fa-gears"></i>
                                            Home
                                        </a>
                                	<span>
                                		<a class="dropdown-item" href="{{ route('profiles.show', \Auth::user()->username) }}">
                                            <i class="fa fa-user-circle"></i>
                                            Profile
                                        </a>
                                	</span>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-arrow-circle-o-right"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

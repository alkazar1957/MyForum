@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
            @include('threads._list')
        </div>

        <div class="col-md-3">
<!--             <div class="card">
                <div class="card-header">
                    Search 
                </div>
                <div class="card-body form-group">
                    <form action="/threads/search" method="get" accept-charset="utf-8">
                        @csrf
                        <input class="form-control input-search" type="text" name="q" placeholder="Search for Something..">
                        <button type="Submit" class="btn btn-xs btn-primary pull-right mt-2">Search</button>
                    </form>
                </div>
            </div>
 -->            @if(count($trending))
                <div class="card">
                    <div class="card-header">
                        Trending Threads
                    </div>
    
                    <div class="card-body">
                        <ul class="list-group">
                        @foreach($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{url($thread->path)}}">
                                    {{ $thread->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    Threads List
                </div>
                <div class="card-body">
                    <a class="dropdown-item" href="/threads">All Threads</a>
                    @foreach($channels as $channel)
                        <a class="dropdown-item" href="/threads/{{$channel->slug}}">{{ $channel->name }}</a>
                    @endforeach
                </div>
            </div>


        </div>


    </div>
</div> 	

@endsection
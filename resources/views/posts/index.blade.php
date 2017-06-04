@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach ($posts as $post)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <h4 class="flex">
                                    <a href="javascript:;">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="body">{{ $post->body }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach ($posts as $post)
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">
                                    {{ $post->title }}
                            </h4>
                            <div class="btn-group pull-right">

                                <a href="{{ route('edit.get', $post->id) }}" class="btn btn-default btn-sm pull-left">Edit</a>

                                <form action="{{ route('delete.post', $post->id) }}" method="post" class="pull-left">

                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete" />
                                    <input class="btn btn-default btn-sm" onclick="return confirm('Are you sure?')" type="submit" value="Delete">
                                </form>
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

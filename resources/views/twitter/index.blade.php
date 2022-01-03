 
@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>Searching Tweet</h1>
@stop

@section('content')
     @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning" role="alert">
            {{ session('warning') }}
        </div>
    @endif
    <div style="margin-bottom: 20px;">
        <form action="{{ route('twitter.search') }}" method="post">
            @csrf
            <div class="input-group input-group">
                <input type="text" name="tweetSearch" class="form-control" placeholder="Search Tweet">
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Search</button>
                </span>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
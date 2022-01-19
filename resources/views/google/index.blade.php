 
@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>User Management</h1>
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
        <a href="{{route('user.create')}}" class="btn btn-success">Add User</a>
    </div>
    <script async src="https://cse.google.com/cse.js?cx=7e1f69d721d893fcd"></script>
    <div class="gcse-search"></div>
    <div class="gcse-searchresults-only">
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
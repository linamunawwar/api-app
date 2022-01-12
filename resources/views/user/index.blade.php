 
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
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Nama</td>
                <td>Email</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key=>$user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <a href="{{route('user.edit',['id' => $user->id])}}" class="btn btn-warning">Edit</a>
                        <a href="{{route('user.edit_pass',['id' => $user->id])}}" class="btn btn-primary">Edit Password</a>
                        <a href="{{route('user.delete',['id' => $user->id])}}" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
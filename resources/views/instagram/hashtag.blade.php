 
@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>Searching Hashtag</h1>
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
        <form action="{{ route('instagram.hashtag_post') }}" method="post">
            @csrf
            <div class="input-group input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Hashtag">
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Search</button>
                </span>
            </div>
        </form>
    </div>
    @if(isset($data))
        <table id="tweetSearch" class="table table-striped table-bordered text-center" style="width: 100%">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Post</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$data['user'][1]}}</td>
                    <td>{{$data['post'][1]}}</td>
                </tr>
                <tr>
                    <td>{{$data['user'][2]}}</td>
                    <td>{{$data['post'][2]}}</td>
                </tr>
                <tr>
                    <td>{{$data['user'][3]}}</td>
                    <td>{{$data['post'][3]}}</td>
                </tr>
                <tr>
                    <td>{{$data['user'][4]}}</td>
                    <td>{{$data['post'][4]}}</td>
                </tr>
                <tr>
                    <td>{{$data['user'][5]}}</td>
                    <td>{{$data['post'][5]}}</td>
                </tr>
            </tbody>
        </table>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
@stop

@push('js')
    <script src = "https://code.jquery.com/jquery-3.5.1.js" ></script>
    <script src = "https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

    <script> 
        $(function () {
            $("#tweetSearch").DataTable();
        });
    </script>
@endpush
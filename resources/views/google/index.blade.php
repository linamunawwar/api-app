 
@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>Google Search</h1>
@stop

@section('content')
<form action="{{url('google-search')}}" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="text" name="query" class="query" id="query" placeholder="search">
    <input type="text" name="site" class="site" id="site" placeholder="Site yang ingin di jelajah">
    <button type="submit" name="submit" class="submit">Search</button>
</form>
<div class="result"></div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src = "https://code.jquery.com/jquery-3.5.1.js" ></script>
    <script src = "https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).on("click", "button.submit", function(e){
            e.preventDefault();
            var query = $('#query').val();
            var site = $('#site').val();
            var _token = $('#_token').val();
            $.ajax({
                type: "post",
                url: '{{ url("google-search") }}',
                data: {"_token" : _token, "query" : query, "site" : site},
                success: function(response){
                    if(response != false){
                     console.log(response);        
                    }
                }
            })
        });
    </script>
@stop
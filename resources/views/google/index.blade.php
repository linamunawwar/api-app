 
@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <style type="text/css">
                body{
        background:#eee;
        }                
        .ibox-content {
            background-color: #FFFFFF;
            color: inherit;
            padding: 15px 20px 20px 20px;
            border-color: #E7EAEC;
            border-image: none;
            border-style: solid solid none;
            border-width: 1px 0px;
        }

        .search-form {
            margin-top: 10px;
        }

        .search-result h3 {
            margin-bottom: 0;
            color: #1E0FBE;
        }

        .search-result .search-link {
            color: #006621;
        }

        .search-result p {
            font-size: 12px;
            margin-top: 5px;
        }

        .hr-line-dashed {
            border-top: 1px dashed #E7EAEC;
            color: #ffffff;
            background-color: #ffffff;
            height: 1px;
            margin: 20px 0;
        }

        h2 {
            font-size: 24px;
            font-weight: 100;
        }           
    </style>
    <h1>Google Search</h1>
@stop

@section('content')
<form action="{{url('google-search')}}" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="text" name="query" class="query" id="query" placeholder="search">
    <input type="text" name="site" class="site" id="site" placeholder="Site yang ingin di jelajah">
    <button type="submit" name="submit" class="submit">Search</button>
</form>
 <img src="{{url('spinner.gif')}}" class="loading" style="display: none; width: 20%; padding: 50px;margin-left: 100px;">
 <input type="hidden" name="searchTerms" id="searchTerms" value=""/>
 <div class="result">
</div>
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
            $('.result').html('');
            $('.loading').show();
            var query = $('#query').val();
            var site = $('#site').val();
            var _token = $('#_token').val();
            $.ajax({
                type: "post",
                url: '{{ url("google-search") }}',
                data: {"_token" : _token, "query" : query, "site" : site},
                success: function(response){
                    $('.loading').hide();
                    if(response != false){
                         console.log(response);
                         $('#searchTerms').val(response.queries.request[0].searchTerms);
                         var result ='';
                         result += '<div class="container bootstrap snippets bootdey">';
                         result += '    <div class="row">';
                         result += '        <div class="col-lg-12">';
                         result += '           <div class="ibox float-e-margins">';
                         result += '                <div class="ibox-content">';
                         result += '                    <h2>';
                         result += '                       100 results found for: <span class="text-navy">'+query+'</span>';
                         result += '                    </h2>';
                         for (var i = 0;i < response.items.length ; i++) {
                             result += '                   <div class="hr-line-dashed"></div>';
                             result += '                    <div class="search-result">';
                             result += '                        <h3><a href="#">'+response.items[i].title+'</a></h3>';
                             result += '                        <a href="'+response.items[i].link+'" class="search-link">'+response.items[i].link+'</a>';
                             result += '                        <p>'+response.items[i].snippet+'</p>';
                             result += '                    </div>';
                        }
                        result += '                </div>';
                        result += '           </div>';
                        result += '         </div>';
                        result += '     </div>';
                        result += ' </div>'; 
                        if(response.queries.nextPage){
                            result += '<a href="" id="pageLink" style="text-align:right; margin:10px; float:right;" class="pageLink btn btn-info" value="'+response.queries.nextPage[0].startIndex+'">Next</a>';
                        }
                        if(response.queries.previousPage){
                            result += '<a href="" id="pageLink" class="pageLink btn btn-info" style="text-align:left; margin:10px; float:left;" value="'+response.queries.previousPage[0].startIndex+'">Prev</a>';
                        }

                        $('.result').html(result);
                    }
                }
            })
        });
        $(document).on("click", "a.pageLink", function(e){
            e.preventDefault();
            $('.result').html('');
            $('.loading').show();
            var query = $('#searchTerms').val();
            console.log(query);
            var startIndex = $(this).attr('value');
            var _token = $('#_token').val();
            $.ajax({
                type: "post",
                url: '{{ url("google-search") }}',
                data: {"_token" : _token, "query" : query, "startIndex" : startIndex},
                success: function(response){
                    $('.loading').hide();
                    if(response != false){
                         console.log(response);
                         $('#searchTerms').val(response.queries.request[0].searchTerms);
                         var result ='';
                         result += '<div class="container bootstrap snippets bootdey">';
                         result += '    <div class="row">';
                         result += '        <div class="col-lg-12">';
                         result += '           <div class="ibox float-e-margins">';
                         result += '                <div class="ibox-content">';
                         result += '                    <h2>';
                         result += '                       '+response.items.length+' results found for: <span class="text-navy">'+query+'</span>';
                         result += '                    </h2>';
                         for (var i = 0;i < response.items.length ; i++) {
                             result += '                   <div class="hr-line-dashed"></div>';
                             result += '                    <div class="search-result">';
                             result += '                        <h3><a href="#">'+response.items[i].title+'</a></h3>';
                             result += '                        <a href="'+response.items[i].link+'" class="search-link">'+response.items[i].link+'</a>';
                             result += '                        <p>'+response.items[i].snippet+'</p>';
                             result += '                    </div>';
                        }
                        result += '                </div>';
                        result += '           </div>';
                        result += '         </div>';
                        result += '     </div>';
                        result += ' </div>'; 
                        result += '<a href="{{ url("google-search/download") }}/'+response.queries.request[0].searchTerms+'/'+response.queries.request[0].startIndex+'" id="download" style="text-align:right; margin:10px; float:center;" class="download btn btn-success">Download</a>';
                        if(response.queries.nextPage){
                            result += '<a href="" id="pageLink" style="text-align:right; margin:10px; float:right;" class="pageLink btn btn-info" value="'+response.queries.nextPage[0].startIndex+'">Next</a>';
                        }
                        if(response.queries.previousPage){
                            result += '<a href="" id="pageLink" class="pageLink btn btn-info" style="text-align:left; margin:10px; float:left;" value="'+response.queries.previousPage[0].startIndex+'">Prev</a>';
                        }

                        $('.result').html(result);
                    }
                }
            })
        });

        $(document).on("click", "a.download", function(e){
            e.preventDefault();
            $('.result').html('');
            $('.loading').show();
            var query = $('#query').val();
            var startIndex = $(this).attr('value');
            var _token = $('#_token').val();
            $.ajax({
                type: "post",
                url: '{{ url("google-search/download") }}',
                data: {"_token" : _token, "query" : query, "startIndex" : startIndex},
                success: function(response){
                    $('.loading').hide();
                }
            })
        });
    </script>
@stop
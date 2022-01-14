 
@extends('adminlte::page')

@section('title', 'Search Tweet')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Browse Tweet</h1> 
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Browse Tweet</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
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

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#userTweet" data-toggle="tab">Data User Tweet</a></li>
                            <li class="nav-item"><a class="nav-link retweetByClick" href="#retweetBy" data-toggle="tab">Retweet By</a></li>
                            <li class="nav-item"><a class="nav-link allRetweetClick" href="#allRetweetUser" data-toggle="tab">All User Retweet</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id_str" id="id_str" value="{{ $id }}">
                        <input type="hidden" name="id_retweet" id="id_retweet">
                        <input type="hidden" name="id_retweetClick" id="id_retweetClick">
                        <input type="hidden" name="id_allRetweetClick" id="id_allRetweetClick">
                        <div class="tab-content">
                            <div class="active tab-pane" id="userTweet">
                                <div class="post">
                                    <div class="user-block">
                                        <span id="image_str">
                                            <img class="img-circle img-bordered-sm" src="" alt="user image">
                                        </span>
                                        <span class="username" id="name_str">
                                            <a href="#">Checking Name</a>
                                        </span>
                                        <span class="description">Shared Date - <span id="tgl_str"> Checking Date </span></span>
                                    </div>
                                    <p id="text_str">
                                        Checking Text
                                    </p>
                                    <hr>
                                    <p>
                                        <table id="user_lookup" class="table table-striped">
                                            <tbody class="data">

                                            </tbody>
                                        </table>
                                    </p>
                                </div>
                            </div>
                            <div class="tab-pane" id="retweetBy">
                                <div class="post">
                                    <div class="user-block">
                                        <span id="image_retweetBy">
                                            <img class="img-circle img-bordered-sm" src="" alt="user image">
                                        </span>
                                        <span class="username" id="name_retweetBy">
                                            <a href="#">Checking Name</a>
                                        </span>
                                        <span class="description">Shared Date - <span id="tgl_retweetBy"> Checking Date </span></span>
                                    </div>
                                    <p id="text_retweetBy">
                                        Checking Text
                                    </p>
                                    <hr>
                                    <p>
                                        <table id="user_retweetBy" class="table table-striped">
                                            <tbody class="data">

                                            </tbody>
                                        </table>
                                    </p>
                                </div>
                            </div>
                            <div class="tab-pane" id="allRetweetUser">
                                <div class="timeline timeline-inverse" id="allRetweet">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
            function newDate(dateObject) {
                var Hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu', 'Minggu'];
                var Bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ];
                
                var d       = new Date(dateObject);
                var nDate   = d.getDate();
                var day     = d.getDay();
                var month   = d.getMonth();
                var year    = d.getYear();
                    year    = (year < 1000) ? year + 1900 : year; 
                
                var date = Hari[day] +", "+ nDate+ " " + Bulan[month] + "  " + year;
            
                return date;
            };    

            function newTime(dateObject) {
                var now     = new Date(dateObject); 

                var hour    = now.getHours();
                var minute  = now.getMinutes(); 
                 
                if(hour.toString().length == 1) {
                     hour = '0'+hour;
                }
                if(minute.toString().length == 1) {
                     minute = '0'+minute;
                }

                var dateTime = hour+':'+minute;   

                 return dateTime;
            }

            $( document ).ready(function(){
                var id = $('#id_str').val();

                $.ajax({
                    type: "get",
                    url: '{{ url('twitter/show') }}'+ "/" + id,
                    data: {},
                    success: function(response){
                        if(response != null || response != []){                            
                            document.getElementById("image_str").innerHTML = "<img class='img-circle img-bordered-sm' src='"+response['user']['profile_image_url']+"' alt='user image'>";
                            document.getElementById("name_str").innerHTML = response['user']['screen_name'];
                            document.getElementById("tgl_str").innerHTML = newDate(response['created_at']);
                            if(response['retweeted_status'] != null){
                                document.getElementById("text_str").innerHTML = response['retweeted_status']['full_text'];
                                $('#id_retweet').val( response['retweeted_status']['id_str']);
                            }else{
                                document.getElementById("text_str").innerHTML = response['full_text'];
                            }

                            var table="<tr>";
                                    table+="<td>Nama : "+ response['user']['name']+"</td>";
                                table+="</tr>"; 
                                table+="<tr>";
                                    table+="<td>Screen Name : "+ response['user']['screen_name']+"</td>";
                                table+="</tr>";          
                                table+="<tr>";
                                    table+="<td>Deskripsi : "+ response['user']['description']+"</td>";
                                table+="</tr>";
                                table+="<tr>";
                                    table+="<td>Lokasi : "+ response['user']['location']+"</td>";
                                table+="</tr>";  
                                table+="<tr>";
                                    table+="<td>Tanggal Pembuatan Twitter : "+ newDate(response['user']['created_at'])+"</td>";
                                table+="</tr>";                    
                                $('#user_lookup tbody.data').append(table);                           
                        }
                    }
                })               
            });

            $(('.nav-pills li a.retweetByClick')).click(function(e){
                var id = $('#id_retweet').val();

                if($('#id_retweetClick').val() != 1){
                    $.ajax({
                        type: "get",
                        url: '{{ url('twitter/show') }}'+ "/" + id,
                        data: {},
                        success: function(response){
                            console.log(response)
    
                            if(response != null || response != []){
                                $('#id_retweetClick').val(1);
                                document.getElementById("image_retweetBy").innerHTML = "<img class='img-circle img-bordered-sm' src='"+response['user']['profile_image_url']+"' alt='user image'>";
                                document.getElementById("name_retweetBy").innerHTML = response['user']['screen_name'];
                                document.getElementById("tgl_retweetBy").innerHTML = newDate(response['created_at']);
                                if(response['retweeted_status'] != null){
                                    document.getElementById("text_retweetBy").innerHTML = response['retweeted_status']['full_text'];
                                    $('#id_retweet').val( response['retweeted_status']['id_retweetBy']);                                    
                                }else{
                                    document.getElementById("text_retweetBy").innerHTML = response['full_text'];
                                }
    
                                var table="<tr>";
                                        table+="<td>Nama : "+ response['user']['name']+"</td>";
                                    table+="</tr>"; 
                                    table+="<tr>";
                                        table+="<td>Screen Name : "+ response['user']['screen_name']+"</td>";
                                    table+="</tr>";          
                                    table+="<tr>";
                                        table+="<td>Deskripsi : "+ response['user']['description']+"</td>";
                                    table+="</tr>";
                                    table+="<tr>";
                                        table+="<td>Lokasi : "+ response['user']['location']+"</td>";
                                    table+="</tr>";  
                                    table+="<tr>";
                                        table+="<td>Tanggal Pembuatan Twitter : "+ newDate(response['user']['created_at'])+"</td>";
                                    table+="</tr>";                    
                                $('#user_retweetBy tbody.data').append(table);                           
                            }
                        }
                    })
                } 
            });

            $(('.nav-pills li a.allRetweetClick')).click(function(e){
                var id = $('#id_retweet').val();
                
                if($('#id_allRetweetClick').val() != 1){
                    $.ajax({
                        type: "get",
                        url: '{{ url('twitter/retweets') }}'+ "/" + id,
                        data: {},
                        success: function(response){    
                            if(response != null || response != []){
                                $('#id_allRetweetClick').val(1);

                                $.each(response,function(i,val){
                                    var div = "<div class='time-label'>";
                                        div += "<span class='bg-danger' id='tgl_allRetweet'> "+newDate(response[i]['created_at'])+" </span>";
                                    div += "</div>";
                                    div += "<div>";
                                        div += "<i class='fab fa-twitter bg-primary'></i>";
                                        div += "<div class='timeline-item'>";
                                            div += "<span class='time'><i class='far fa-clock'></i> Shared Date - "+newTime(response[i]['created_at'])+"</span>";
                                            div += "<h3 class='timeline-header'>";
                                                div += "<img class='img-circle img-bordered-sm' src='"+response[i]['user']['profile_image_url']+"' alt='user image'>";
                                                div += "<span class='username'> <a href='#'>"+response[i]['user']['screen_name']+"</a> </span>";
                                            div += " </h3>";
                                            div += "<div class='timeline-body'>"; 
                                                div += "Lokasi : "+response[i]['user']['location']+" <br>";
                                                div += "Tanggal Pembuatan Twitter : "+newDate(response[i]['user']['created_at'])+"";
                                            div += "</div>";
                                        div += "</div>";
                                    div += "</div>";
                                
                                    $('#allRetweet').append(div);
                                });
                            }
                        }
                    })
                } 
            });
            
        });
    </script>
@endpush
 
@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Search Tweet </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Search Tweet</li>
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
    <div style="margin-bottom: 20px;">
        {{-- <form action="{{ route('twitter.search') }}" method="post"> --}}
            {{-- @csrf --}}
            <div class="input-group input-group">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="notif" id="notif" value="{{ $notif }}">
                <input type="text" id="tweetField" name="tweetField" class="form-control" placeholder="Search Tweet">
                <span class="input-group-append">
                    <button type="button" class="btn btn-info btn-flat-right tweetSearch"><i><span class="fa fa-search"> </span></i> <b>Search</b> </button>
                </span>
            </div>
        {{-- </form> --}}
    </div>

    <table id="tweetSearch" class="table table-striped table-bordered text-center" style="width: 100%">
        <thead>
            <tr>
                <th width="5%"> No </th>
                <th width="10%"> Status </th>
                <th width="40%"> Text Tweet </th>
                <th> Nama User </th>
                <th> Asal Negara </th>
                <th> Tanggal Dibuat </th>
                <th> Action </th>
            </tr>
        </thead>
        <tbody class="data">
            
        </tbody>
    </table>
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
        $(document).ready( function () {
            var notif = $('#notif').val();

            if(notif == 0){
                alert("Untuk Mengakses Twitter, Anda Perlu Login Terlebih Dahulu!!");
                document.location.href = 'media/twitter';
            }
        });

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

            $(document).on("click", "button.tweetSearch", function(e){
                $('#tweetSearch').DataTable().clear().destroy();
                var query = $('#tweetField').val();
                var _token = $('#_token').val();
                var replyOrTweet = "";

                $.ajax({
                    type: "post",
                    url: '{{ url('twitter/search') }}',
                    data: {
                        'tweetField' : query,
                        '_token': _token
                    },
                    success: function(response){
                        console.log(response['statuses'])

                        var jumlahData = 0;
                        $.each(response['statuses'],function(i,val){
                            if(response['statuses'][i]['in_reply_to_status_id'] != null){
                                replyOrTweet = "Reply";
                            }else if(response['statuses'][i]['retweeted_status'] != null){
                                replyOrTweet = "Retweeted";
                            }else{
                                replyOrTweet = "Tweet";
                            }
                            jumlahData++;
                            var table="<tr  class='data_"+jumlahData+"'>";
                                table+="<td>"+jumlahData+"<input type='hidden' name='id_str[]' value='"+response['statuses'][i]['id_str']+"' id='id_str"+jumlahData+"'></td>";
                                table+="<td>"+replyOrTweet+"<input type='hidden' name='replyOrTweet[]' value='"+replyOrTweet+"' id='replyOrTweet_"+jumlahData+"'></td>";
                                table+="<td>"+response['statuses'][i]['text']+"<input type='hidden' name='text[]' value='"+response['statuses'][i]['text']+"' id='text_"+jumlahData+"'></td>";
                                table+="<td>"+response['statuses'][i]['user']['name']+"<input type='hidden' name='userName[]' value='"+response['statuses'][i]['user']['name']+"' id='userName_"+jumlahData+"'></td>";
                                table+="<td>"+response['statuses'][i]['user']['location']+"<input type='hidden' name='userLocation[]' value='"+response['statuses'][i]['user']['location']+"' id='userLocation_"+jumlahData+"'></td>";
                                table+="<td>"+ newDate(response['statuses'][i]['created_at'])+"<input type='hidden' name='date[]' value='"+response['statuses'][i]['created_at']+"' id='date_"+jumlahData+"'></td>";
                                table+="<td>";
                                table+="<button type='button' class='btn btn-info btn-flat-right telusuri' idsub='"+response['statuses'][i]['id_str']+"' id='"+jumlahData+"'>Telusuri</button>";
                                table+="</td>";
                                table+="</tr>";                                
                                $('#tweetSearch tbody.data').append(table);
                        });

                        $('#tweetSearch').DataTable();
                        
                    }
                })               
            });

            $(document).on("click", "button.telusuri", function(e){
                e.preventDefault();
                var id = $(this).attr('idsub');
                var jumlahdata = $('#jumlah_data').val();
                
                var url = "{{  route('twitter.telusuri', ":id")  }}";
                url = url.replace(':id', id);
                window.location.href=url;
            });
            
        });
    </script>
@endpush
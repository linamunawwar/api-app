 
@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>Searching Tweet</h1> {{ Session::get('sessionTweetField') }}
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
                <th> No </th>
                <th> Text Tweet </th>
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
        $(function () {
            function newDate(dateObject) {
                var d = new Date(dateObject);
                var day = String(d.getDate()).padStart(2, '0');
                var month = String(d.getMonth() + 1).padStart(2, '0');
                var year = d.getFullYear();
                
                var date = day + "  " + month + "  " + year;
            
                return date;
            };            

            $(document).on("click", "button.tweetSearch", function(e){
                $('#tweetSearch').DataTable().clear().destroy();
                var query = $('#tweetField').val();
                var _token = $('#_token').val();

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
                            jumlahData++;
                            var table="<tr  class='data_"+jumlahData+"'>";
                                table+="<td>"+jumlahData+"</td>";
                                table+="<td>"+response['statuses'][i]['text']+"<input type='hidden' name='text[]' value='"+response['statuses'][i]['text']+"' id='text_"+jumlahData+"'></td>";
                                table+="<td>"+response['statuses'][i]['user']['name']+"<input type='hidden' name='userName[]' value='"+response['statuses'][i]['user']['name']+"' id='userName_"+jumlahData+"'></td>";
                                table+="<td>"+response['statuses'][i]['user']['location']+"<input type='hidden' name='userLocation[]' value='"+response['statuses'][i]['user']['location']+"' id='userLocation_"+jumlahData+"'></td>";
                                table+="<td>"+ newDate(response['statuses'][i]['created_at'])+"<input type='hidden' name='date[]' value='"+response['statuses'][i]['created_at']+"' id='date_"+jumlahData+"'></td>";
                                table+="<td>";
                                table+="<a class='btn btn-block btn-info' idsub='"+jumlahData+"'>Telusuri</a>";
                                table+="</td>";
                                table+="</tr>";                                
                                $('#tweetSearch tbody.data').append(table);
                        });

                        $('#tweetSearch').DataTable();
                        
                    }
                })               
            });

            
        });
    </script>
@endpush
<?php
function has_children($rows, $id) {
  foreach ($rows as $row) {
      if ($row['parent'] == $id)
          return true;
  }
  return false;
}

function build_menu($rows, $parent = 0, $vert = 0) {
  if ($vert == 'vertical') {
      $result = "<ul type='vertical'>";
  } else {
      $result = "<ul>";
  }
  $i = 0;
  foreach ($rows as $row) {
      if ($row['parent'] == $parent) {
          $result .= "<li><b>{$row['text']}<br></b>  <div class='title'>{$row['title']}</div>";
          if (has_children($rows, $row['posisi']))
              $result .= build_menu($rows, $row['posisi'], $row['dir']);
          $result .= "</li>";
      }
  }
  $result .= "</ul>";

  return $result;
}
?>
 
@extends('adminlte::page')

@section('title', 'Search Tweet')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Struktur Retweet</h1> 
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Struktur Retweet</li>
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
                @if ($response != false)
                    <div class="card-header">
                        <button type="button" class="btn btn-info" id="capture"> Export Image </button>
                    </div>
                @endif
                <div class="card-body">
                  <center>
                    @if ($response != false)
                        <ul id="tree-data" style="display:none">                            
                            <?php echo build_menu($response); ?>
                        </ul>
                      <div id="tree-view" class="tree-view"></div>
                    @else
                        Data Tidak Tersedia
                    @endif	
                  </center>
                </div>
              </div>
          </div>
      </div>
    </div>        
@stop
@section('css')
  <link href="{{ asset('css/orgchart/orgchart.css') }}" rel="stylesheet" type="text/css"/>
@stop

@push('js')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('css/orgchart/orgchart.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        $(document).ready(function () {
            
            // create a tree
            $("#tree-data").jOrgChart({
                chartElement: $("#tree-view"),
                nodeClicked: nodeClicked
            });

            // lighting a node in the selection
            function nodeClicked(node, type) {
                node = node || $(this);
                $('.jOrgChart .selected').removeClass('selected');
                node.addClass('selected');
            }
        });

        $(function(){    
            
            //to make a div draggable
            $('.tree-view').draggable(
                {containment: "#canvas", scroll: false}
            );
            
            $('#capture').click(function(){
                //get the div content
                html2canvas(document.getElementById("tree-view")).then(function(canvas) {                    
                    var anchorTag = document.createElement("a");
                    document.body.appendChild(anchorTag);
                    anchorTag.download = "diagram.jpg";
                    anchorTag.href = canvas.toDataURL();
                    anchorTag.target = '_blank';
                    anchorTag.click();
                });
            });            
        });
    </script>
@endpush

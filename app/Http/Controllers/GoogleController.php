<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class GoogleController extends Controller
{
    public function index()
    {
        return view('google.index');
    }

    public function search(Request $request)
    {
        $query = $request['query'];
        if($request['site'] != ''){
            $site = $request['site'];
            $response = Http::get("https://www.googleapis.com/customsearch/v1?key=AIzaSyCDX4_dkyim-R5GurDRtWzD2PEeQD3SVqs&cx=2cf421576b7ce23aa&q=site:$site $query");
        }elseif($request['startIndex'] != ''){
            $startIndex = $request['startIndex'];
            $response = Http::get("https://www.googleapis.com/customsearch/v1?key=AIzaSyCDX4_dkyim-R5GurDRtWzD2PEeQD3SVqs&cx=2cf421576b7ce23aa&q=$query&start=$startIndex");
        }else{
            $response = Http::get("https://www.googleapis.com/customsearch/v1?key=AIzaSyCDX4_dkyim-R5GurDRtWzD2PEeQD3SVqs&cx=2cf421576b7ce23aa&q=$query");
        }
        
        if($response->status() == 200){
            $result = json_decode($response);
            return $result;
        }else{
            return false;
        }
        
    }

    public function download($query,$startIndex){
        if($startIndex != ''){
            $response = Http::get("https://www.googleapis.com/customsearch/v1?key=AIzaSyCDX4_dkyim-R5GurDRtWzD2PEeQD3SVqs&cx=2cf421576b7ce23aa&q=$query&start=$startIndex");
        }else{
            $response = Http::get("https://www.googleapis.com/customsearch/v1?key=AIzaSyCDX4_dkyim-R5GurDRtWzD2PEeQD3SVqs&cx=2cf421576b7ce23aa&q=$query");
        }
        
        if($response->status() == 200){
            $result = json_decode($response, true);
            $pdf = PDF::loadView('google.pdf', $result);
            return $pdf->download('google-result.pdf');
        }else{
            return false;
        }

         
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function homePage(Request $request){
        $data = [
            'pageTitle'=>'SMKN 4 MarketPlace'
        ];
        return view('front.pages.home',$data);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function root(Request $request)
    {

//        $user = Auth::user();
//        $id = Auth::id();
//        if (Auth::check($user)) {
//            dd(1);
//        }
//        $data = $request->session()->all();
        return view('pages.root');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    //home
    public function home(){
        return view('static_pages/home');
    }

    //list
    public function category(){
        return view('static_pages/category');
    }

    //help
    public function help(){
        return view('static_pages/help');
    }

    //about
    public function about(){
        return view('static_pages/about');
    }

    public function login(){
        return view('static_pages/login');
    }

    public function signup(){
        return view('static_pages/signup');
    }
}

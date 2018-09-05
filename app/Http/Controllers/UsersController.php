<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    //用户功能
    public function create()
    {
        return view('users.create');
    }
}

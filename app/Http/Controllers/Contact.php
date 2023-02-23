<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Contact extends Controller
{
    public function index()
    {
        return view('velocity.views.home.contact');
    }

    public function testForm(Request $request)
    {
        dd($request->request);
    }
}

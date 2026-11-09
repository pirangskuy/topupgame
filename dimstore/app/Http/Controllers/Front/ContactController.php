<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        // layout kamu sudah ambil SiteSetting, jadi view ini cukup tampilkan saja
        return view('front.contact');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return response()->json([
            'app_name' => 'Tujjar backend api',
            'description' => 'Tujjar App is a platform for small businesses to advertise their products and services to the public.',
            'version' => '1.0.0',
            'author' => 'Tujjar App',
            'author_url' => 'https://tujjar.ma',
            'author_email' => 'a.elfedali@gmail.com'    


        ]);
    }
}

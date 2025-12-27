<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

//F16 - Evan Yuvraj Munshi
class CommunityController extends Controller
{
    public function index()
    {
        return view('community.index');
    }
}


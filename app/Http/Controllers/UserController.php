<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke()
    {
        Auth::user()->token()->revoke();

        return response()->json(null,200);
    }
}

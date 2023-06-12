<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Excel;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('welcome', compact('users'));
    }

    function import(Request $request)
    {
        Validator::make($request, [
            'excelFile' => 'required|mimes:xlsx,xls'
        ]);

        
    }
}

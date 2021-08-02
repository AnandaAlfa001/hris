<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function showProfile(int $id)
    {
        return view('company/profile');
    }
}

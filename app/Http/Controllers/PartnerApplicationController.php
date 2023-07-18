<?php

namespace App\Http\Controllers;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;


class PartnerApplicationController extends Controller
{
    public function index()
    {
        $Applications = Company::whereHas('user', function ($query) {
            $query->where('level', '!=', 'admin');
        })->get();

        return view('admin.manage.application',compact('Applications'));
 
    }

     public function profile($id)
    {
       
        $partner = User::with('company')->findOrFail($id);

        return view('admin.manage.profile', compact('partner'));
    }
}

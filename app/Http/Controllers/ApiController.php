<?php

namespace App\Http\Controllers;

use App\Bay;
use App\Garden;
use App\Grade;
use App\Owner;
use App\Package;
use App\User;
use App\Warehouse;

class ApiController extends Controller
{
    public function apiGrades()
    {
        return Grade::all();
    }

    public function apiPackages()
    {
        return Package::all();
    }

    public function apiGardens()
    {
        return Garden::all();
    }

    public function apiWarehouses()
    {
        return Warehouse::all();
    }

    public function apiBays()
    {
        return Bay::all();
    }

    public function apiOwners()
    {
        return Owner::all();
    }

    public function apiUsers()
    {
        return User::all();
    }
}

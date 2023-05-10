<?php

namespace App\Http\Controllers;

use App\Bay;
use App\Customer;
use App\Grade;
use App\Package;
use App\Qty;
use App\Stock;
use App\User;
use App\Warehouse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function apiPackages(Request $request)
    {
        return Package::all();
    }

    public function apiCategories()
    {
        return Qty::all();
    }

    public function apiGrades()
    {
        return Grade::all();
    }

    public function apiCustomers()
    {
        return Customer::all();
    }

    public function apiWarehouses()
    {
        return Warehouse::all();
    }

    public function apiBays()
    {
        return Bay::all();
    }

    public function apiStock()
    {
        return Stock::all();
    }

    public function apiUsers()
    {
        return User::all();
    }
}

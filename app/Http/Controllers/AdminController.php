<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminUserCreated;
use App\Models\User;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function loginSubmit(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password) && $user->is_admin) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    // public function showDashboard()
    // {
    //     if (Auth::check() && Auth::user()->is_admin) {
    //         return view('admin.dashboard');
    //     }
    //     return redirect()->route('admin.login')->with('error', 'You must be logged in as admin to access the dashboard.');
    // }

    public function createAdminUser(Request $request)
    {

        $password = 'mypassword';

        // Hash the password using Laravel's built-in Hash class
        $hashedPassword = Hash::make($password);

        // Create a new user with the email and hashed password
        $user = new User([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => $hashedPassword,
            'is_admin' => true,
        ]);

        // Save the user to the database
        $user->save();

        return redirect()->back()->with('success', 'Admin user created successfully!');
    }
}

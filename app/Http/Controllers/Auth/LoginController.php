<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt:', $request->all());

        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            Log::info('Login successful:', ['user' => Auth::user()]);
            return $this->sendLoginResponse($request);
        }

        Log::error('Login failed:', ['errors' => 'Invalid credentials']);
        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::attempt([
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only('phone', 'remember'))
            ->withErrors(['message' => 'Invalid credentials.']);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    public function username()
    {
        return 'phone';
    }
}

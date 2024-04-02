<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();

            if ($user && $user->isClerk() && (!$user->token || $user->token_expires_at < Carbon::now())) {
                $this->guard()->logout();
                $request->session()->invalidate();
                return redirect()->back()->withErrors(['message' => 'Access restricted.']);
            }

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }



    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $user = $this->guard()->user();
        if ($user->isClerk() && $user->isTokenEnabled()) {
            $user->generateToken();
        }

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    public function generateTokens(Request $request)
    {
        $selectedUsers = $request->input('selectedUsers');

        // Generate tokens for selected users
        User::whereIn('id', $selectedUsers)->each(function ($user) {
            $user->generateToken();
        });

        return response()->json(['message' => 'Tokens generated successfully']);
    }

    public function toggleTokenActivation(Request $request)
    {
        $selectedUsers = $request->input('selectedUsers');

        // Update token activation for selected users
        User::whereIn('id', $selectedUsers)->update([
            'token_activated' => DB::raw('NOT token_activated')
        ]);

        return response()->json(['message' => 'Token activation toggled successfully']);
    }
}


<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\PhoneValidationRule;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/user';

    public function __construct()
    {
        $this->middleware('role:admin');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', new PhoneValidationRule(), 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,staff,clerk'],
        ]);
    }

    protected function create(array $data)
    {
        $request = request();

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($user->isClerk()) {
            // Generate token for clerk user
            $user->generateToken();
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('success', 'User created successfully.');
    }


    protected function registered(Request $request, $user)
    {
        // Custom logic after user registration, if needed
    }
}

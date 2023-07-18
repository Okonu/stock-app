<?php

namespace App\Http\Controllers;

use App\Models\CompanyType;
use App\Models\Location;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    
  

    public function register()
    {
        $locations = Location::all();
        $companyTypes = CompanyType::all();

        return view('auth.register', compact('locations', 'companyTypes'));
    }


       public function accountinfo()
    {
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();
        $companyTypes = CompanyType::all();
        $locations = Location::all();

        return view('profile', compact('user', 'company', 'companyTypes', 'locations'));
    }

    public function adminaccountinfo()
    {
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();
        $companyTypes = CompanyType::all();
        $locations = Location::all();

        return view('admin.profile', compact('user', 'company', 'companyTypes', 'locations'));
    }


    public function registerSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'company_name' => 'required',
            'location' => 'required',
            'company_type' => 'required',
            'business_email' => 'required|email',
            'telephone' => 'required',
            'business_registration_files' => 'required',
            'business_identification_number' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'pending',
            'registration_status' => false,
        ]);

        $user->company()->create([
            'name' => $request->company_name,
            'location_id' => $request->location,
            'company_type_id' => $request->company_type,
            'business_email' => $request->business_email,
            'telephone' => $request->telephone,
            'business_registration_files' => $request->business_registration_files,
            'business_identification_number' => $request->business_identification_number,
        ]);

        return redirect()->route('login')->with('message', 'Registration successful. Your account is pending approval.');
    }

     public function updatepartner(Request $request)
        {
            $validatedData = $request->validate([
                'company_name' => 'required',
                'business_email' => 'required|email',
                'telephone' => 'required',
                'location' => 'required',
                'business_identification_number' => 'required',
                'company_type' => 'required',
            ]);

            $userId = Auth::id();

            User::where('id', $userId)->update([
                'name' => $request->input('company_name'),
                'email' => $request->input('business_email'),
            ]);

            DB::table('companies')
                ->where('user_id', $userId)
                ->update([
                    'name' => $request->input('company_name'),
                    'location_id' => $request->input('location'),
                    'company_type_id' => $request->input('company_type'),
                    'business_email' => $request->input('business_email'),
                    'telephone' => $request->input('telephone'),
                    'business_identification_number' => $request->input('business_identification_number'),
                ]);

            return redirect()->back()->with('success', 'Profile updated successfully');
        }



         public function updateadmin(Request $request)
        {
            $validatedData = $request->validate([
                'company_name' => 'required',
                'business_email' => 'required|email',
                'telephone' => 'required',
                'location' => 'required',
                'business_identification_number' => 'required',
                'company_type' => 'required',
            ]);

            $userId = Auth::id();

            User::where('id', $userId)->update([
                'name' => $request->input('company_name'),
                'email' => $request->input('business_email'),
            ]);

            DB::table('companies')
                ->where('user_id', $userId)
                ->update([
                    'name' => $request->input('company_name'),
                    'location_id' => $request->input('location'),
                    'company_type_id' => $request->input('company_type'),
                    'business_email' => $request->input('business_email'),
                    'telephone' => $request->input('telephone'),
                    'business_identification_number' => $request->input('business_identification_number'),
                ]);

            return redirect()->back()->with('success', 'Profile updated successfully');
        }

    public function updateProfilePicture(Request $request)
        {
            $user = auth()->user();
            $company = $user->company;

            // Handle the uploaded file and generate a unique file name
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');
                $profilePictureFileName = uniqid().'.'.$profilePicture->getClientOriginalExtension();
                $profilePicture->storeAs('public/profile_pictures', $profilePictureFileName);

                // Update the profile_pic column of the associated company
                $company->profile_pic = $profilePictureFileName;
                $company->save();
            }

            // Redirect or perform additional actions as needed
            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }

        public function updateAdminProfilePicturea(Request $request)
        {
            $user = auth()->user();
            $company = $user->company;

            // Handle the uploaded file and generate a unique file name
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');
                $profilePictureFileName = uniqid().'.'.$profilePicture->getClientOriginalExtension();
                $profilePicture->storeAs('public/profile_pictures', $profilePictureFileName);

                // Update the profile_pic column of the associated company
                $company->profile_pic = $profilePictureFileName;
                $company->save();
            }

            // Redirect or perform additional actions as needed
            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }

    // public function updateProfilePictureAjax(Request $request)
    // {
    //     $request->validate([
    //         'profile_picture' => 'image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     $user = Auth::user();

    //     if ($request->hasFile('profile_picture')) {
    //         $profilePicture = $request->file('profile_picture');
    //         $filename = time() . '.' . $profilePicture->getClientOriginalExtension();

    //         $profilePicture->storeAs('public/profile_pictures', $filename);

    //         $previousProfilePicture = $user->company->profile_pic;
    //         if ($previousProfilePicture) {
    //             Storage::delete('public/profile_pictures/' . $previousProfilePicture);
    //         }

    //         $user->company->profile_pic = $filename;
    //         $user->company->save();

    //         return response()->json(['success' => 'Profile picture updated successfully', 'filename' => $filename]);
    //     }

    //     return response()->json(['error' => 'Failed to update profile picture'], Response::HTTP_BAD_REQUEST);
    // }

    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->level === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->registration_status) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('auth.pending')->with('message', 'Your registration is pending approval or has been rejected.');
            }
        }

        throw ValidationException::withMessages(['email' => trans('auth.failed')]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/auth/login');
    }

    public function pendingApplications(Request $request)
    {
        $users = User::where('registration_status', false)->get();

        return response()->json(['users' => $users]);
    }

    public function approveUser(Request $request, $userId)
    {
        $request->validate([
            'approve' => 'required|in:partner,admin',
        ]);

        $user = User::findOrFail($userId);
        $level = $request->input('approve');
        $user->level = $level;
        $user->registration_status = $level === 'partner' || $level === 'admin';

        $user->save();

        return redirect()->back();
        // return response()->json([
        //     'user' => $user,
        //     'status' => $user->registration_status,
        //     'level' => $user->level,
        //     'company_name' => $user->company->name,
        // ]);
    }

public function getAllUsers()
{
    $users = User::where('id', '!=', 1)
        ->with('company')
        ->get(['id', 'name', 'email', 'registration_status', 'level']);

    return redirect()->route('admin.manage.application')->with('users', $users);
}


    public function deactivateUser(Request $request, $userId)
    {
        $request->validate([
            'deactivate' => 'required|boolean',
        ]);

        $user = User::findOrFail($userId);
        $user->registration_status = $request->input('deactivate') ? 'deactivated' : 'approved';

        if ($user->registration_status === 'deactivated') {
            $user->level = 'pending';
        }

        $user->save();

        return response()->json([
            'user' => $user,
            'status' => $user->registration_status,
            'level' => $user->level,
        ]);
    }

     public function rejectUser(Request $request, $userId)
    {
        
    }
}

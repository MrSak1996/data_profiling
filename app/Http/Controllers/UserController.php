<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens; // Import HasApiTokens trait
use Illuminate\Support\Facades\Validator;



use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{




    public function login(Request $request)
    {
        $hashPass = hash('sha256', $request->password);
        $user = User::where('username', $request->input('username'))
            ->where('password', $hashPass)
            ->first();
        // print_r($user);

        if ($user) {
            $token = $user->createToken('auth-token')->plainTextToken;

            User::where('id', $user->id)
                ->update([
                    'api_token' => $token
                ]);
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'api_token' => $token,
                'user_role' => $user->user_role,
                'userId' => $user->id,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed',
            ]);
        }
    }

    public function logout()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->tokens()->delete(); // Invalidate all user tokens
        }

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    public function getProfileInfo(Request $request)
    {
        $user_id = $request->query('user_id');
        $api_token = $request->query('api_token');
        $query = DB::table('users as u')
            ->select(
              'u.'
            )
            ->join('dp_onbint_staging as s', 's.RSBSASYSTEMGENERATEDNUMBER', '=', 'v.rsbsa_no')
            ->get();
        return response()->json($query);

        if (!$user) {
            return Response::json(['error' => 'User not found'], 404);
        }
       
        return Response::json($responseData, 200);
    }
    public function getAgency()
    {
        $query = DB::table('dp_onbint_agency')
        ->select(
         'ID',
         'AGENCY'
        )
        ->get();
        return response()->json($query);
    }
    public function getRegionOffice()
    {
        $query = DB::table('dp_onbint_region')
        ->select(
         'ID',
         'INFO_REGION'
        )
        ->get();
        return response()->json($query);
    }
    public function getServiceInfo()
    {
        $query = DB::table('dp_onbint_service')
        ->select(
         'ID',
         'INFO_SERVICE'
        )
        ->get();
        return response()->json($query);
    }
    public function getDivision()
    {
        $query = DB::table('dp_onbint_division')
        ->select(
         'ID',
         'INFO_DIVISION'
        )
        ->get();
        return response()->json($query);
    }
    
    public function createUser(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'complete_address' => 'required|string|max:255',
            'sex' => 'required|integer|in:1,2',
            'birthdate' => 'required|date',
            'mobile_number' => 'required|string|regex:/^[0-9]{10,15}$/',
            'email_address' => 'required|string|email|max:255|unique:users,email',
            'user_role' => 'required|integer|in:1,2,3',
        ]);

        // If validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create a new user
            $user = User::create([
                'firstname' => $request->input('firstname'),
                'middlename' => $request->input('middlename'),
                'lastname' => $request->input('lastname'),
                'province' => $request->input('province'),
                'region' => $request->input('region'),
                'complete_address' => $request->input('complete_address'),
                'sex' => $request->input('sex'),
                'birthdate' => $request->input('birthdate'),
                'mobile_number' => $request->input('mobile_number'),
                'email' => $request->input  ('email_address'),
                'password' => Hash::make('defaultPassword'), 
                'user_role' => $request->input('user_role'),// Example password, change as needed
            ]);

            // Return a success response
            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['message' => 'User creation failed', 'error' => $e->getMessage()], 500);
        }
    }


    // public function fetchUserData($userId)
    // {
    //     $query = User::selectRaw('
    //         pmo.pmo_title,
    //         pmo.id,
    //         DIVISION_COLOR,
    //         tblposition.position_title,
    //         CONCAT(users.last_name," ", users.first_name," ",users.middle_name)  as name,
    //         users.email as email
    //         ')
    //         ->leftJoin('pr', 'pr.action_officer', '=', 'users.id')
    //         ->leftJoin('pmo', 'pmo.id', '=', 'users.pmo_id')
    //         ->leftJoin('tblposition', 'tblposition.POSITION_C', '=', 'users.position_id')
    //         ->where('users.id', $userId);



    //     // Optionally, you can print the SQL query to check
    //     // dd($query->toSql());

    //     // Execute the query and return the result
    //     $userData = $query->first(); // Use first() instead of get() to retrieve a single result
    //     return response()->json($userData);
    // }

    // public function getUserDetails($id)
    // {
    //     $query = User::selectRaw('
    //         users.id as id,
    //         users.last_name,
    //         users.middle_name,
    //         users.first_name,
    //         users.ext_name,
    //         users.gender,
    //         users.birthdate,
    //         users.contact_details,
    //         users.email,
    //         users.employment_status,
    //         users.employee_no,
    //         users.username,
    //         users.user_role,
    //         users.pmo_id,
    //         users.position_id,
    //         p.pmo_title as office,
    //         pos.POSITION_TITLE as position

    //         ')
    //         ->leftJoin('pmo as p', 'p.id', '=', 'users.pmo_id')
    //         ->leftJoin('tblposition as pos', 'pos.POSITION_C', '=', 'users.position_id')
    //         ->where('users.id', $id);
    //     $data = $query->first(); // Use first() instead of get() to retrieve a single result
    //     return response()->json($data);
    // }

    // public function updateUserDetails(Request $request)
    // {
    //     // Validate the incoming request
    //     // $request->validate([
    //     //     'id' => 'required|integer',
    //     //     'employee_no' => 'nullable|string',
    //     //     'pmo_id' => 'nullable|integer',
    //     //     'position_id' => 'nullable|integer',
    //     //     'province' => 'nullable|string',
    //     //     'city' => 'nullable|string',
    //     //     'barangay' => 'nullable|string',
    //     //     'employment_status' => 'nullable|string',
    //     //     'first_name' => 'nullable|string',
    //     //     'middle_name' => 'nullable|string',
    //     //     'last_name' => 'nullable|string',
    //     //     'ext_name' => 'nullable|string',
    //     //     'birthdate' => 'nullable|date',
    //     //     'gender' => 'nullable|string',
    //     //     'contact_details' => 'nullable|string',
    //     //     'email' => 'nullable|string|email',
    //     //     'username' => 'nullable|string',
    //     // ]);

    //     // Hash the password if it is provided
    //     // Check if input password is provided and not empty
    //     if (!empty($request->input('password'))) {
    //         $hashedPassword = hash('sha256', $request->input('password'));
    //     } else {
    //         // If password is not provided or empty, retrieve the existing hashed password
    //         $existingUser = User::find($request->input('id'));
    //         $hashedPassword = $existingUser->password;
    //     }

    //     // Update the user record
    //     User::where('id', $request->input('id'))
    //         ->update([
    //             'employee_no' => $request->input('employee_no'),
    //             'pmo_id' => $request->input('pmo_id'),
    //             'position_id' => $request->input('position_id'),
    //             // 'province' => $request->input('province'),
    //             // 'city' => $request->input('city'),
    //             'isUpdatedPassword' => 1,
    //             'employment_status' => $request->input('employment_status'),
    //             'first_name' => $request->input('first_name'),
    //             'middle_name' => $request->input('middle_name'),
    //             'last_name' => $request->input('last_name'),
    //             'ext_name' => $request->input('ext_name'),
    //             'birthdate' => $request->input('birthdate'),
    //             'gender' => $request->input('gender'),
    //             'contact_details' => $request->input('contact_details'),
    //             'email' => $request->input('email'),
    //             'username' => $request->input('username'),
    //             'password' => $hashedPassword,
    //         ]);

    //     return response()->json(['message' => 'User details updated successfully']);
    // }








}

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
        // Retrieve the user by username
        $user = User::where('username', $request->input('username'))->first();
        // Check if user exists and verify the password
        if ($user && Hash::check($request->password, $user->password)) {
            // Create an API token
            $token = $user->createToken('auth-token')->plainTextToken;

            // Update the user with the new token if needed (but typically not necessary)
            $user->update([
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
                'message' => 'Invalid credentials',
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

    public function getUserAccount()
    {
        $profiles = DB::table('users as u')
            ->leftJoin('dp_onbint_agency as a', 'a.ID', '=', 'u.id_agency')
            ->leftJoin('dp_onbint_region as r', 'r.ID', '=', 'u.id_region')
            ->select(
                'u.id',
                'u.first_name',
                'u.middle_name',
                'u.last_name',
                'u.ext_name',
                'a.AGENCY as agency',
                'r.INFO_REGION as office',
                DB::raw("CASE WHEN u.sex = 1 THEN 'MALE' WHEN u.sex = 2 THEN 'FEMALE' END AS sex"),
                'u.date_of_birth',
                DB::raw("CASE WHEN u.account_status = 1 THEN 'ACTIVE' ELSE 'INACTIVE' END as account_status"),
                DB::raw("CASE 
                WHEN u.user_role = 1 THEN 'SUPER ADMIN'
                WHEN u.user_role = 2 THEN 'ADMIN'
                WHEN u.user_role = 3 THEN 'USER VALIDATOR'
                END as user_role"),
                'u.position',
                'u.contact_no',
                'u.complete_address',
                'u.email',
                'u.brgy_code',
                'u.mun_code',
                'u.province_code',
                'u.region_code'
            )
            ->get();

        return response()->json($profiles);
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
        $validatedData = $request->validate([
            'agency' => 'required|integer',
            'office' => 'required|integer',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'ext_name' => 'nullable|string|max:10',
            'sex' => 'required|in:1,2', // Assuming 1=Male, 2=Female
            'birthdate' => 'required|date',
            'emp_status' => 'required|integer',
            'position' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'complete_address' => 'required|string|max:255',
            'barangay' => 'required|string|max:100',
            'municipality' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'email_address' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8',
            'user_role' => 'required|integer',
        ]);

        // Check if the program_id exists
        $programId = 31; // Example program_id
        $programExists = DB::table('dp_onbint_programs')->where('id', $programId)->exists();

        if (!$programExists) {
            return response()->json(['message' => 'Invalid program ID. User creation failed.'], 400);
        }

        // Create a new user
        try {
            $user = User::create([
                'id_agency' => $validatedData['agency'],
                'id_region' => $validatedData['office'],
                'program_id' => $programId,
                'agency_loc' => null, // or set a value if available
                'first_name' => $validatedData['firstname'],
                'middle_name' => $validatedData['middlename'],
                'last_name' => $validatedData['lastname'],
                'ext_name' => $validatedData['ext_name'],
                'sex' => $validatedData['sex'],
                'date_of_birth' => $validatedData['birthdate'],
                'account_status' => '1',
                'emp_status' => $validatedData['emp_status'],
                'position' => $validatedData['position'],
                'contact_no' => $validatedData['mobile_number'],
                'complete_address' => $validatedData['complete_address'],
                'brgy_code' => $validatedData['barangay'],
                'mun_code' => $validatedData['municipality'],
                'province_code' => $validatedData['province'],
                'region_code' => $validatedData['region'],
                'email' => $validatedData['email_address'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'user_role' => $validatedData['user_role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function addRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'roles' => 'required|integer', 
        ]);
        User::where('id', $request->input('user_id'))
            ->update([
                'program_id' => $request->input('roles'),
            ]);
        return response()->json(['message' => 'Updated successfully']);
    }

    public function getUserDetails()
    {
        $query = DB::table('users as u')
            ->leftJoin('dp_onbint_programs as p', 'p.id', '=', 'u.program_id')
            ->select(
                'u.id',
                'u.first_name',
                'u.middle_name',
                'u.last_name',
                DB::raw("
                    CASE 
                        WHEN u.user_role = 1 THEN 'Super Admin'
                        WHEN u.user_role = 2 THEN 'Admin'
                        WHEN u.user_role = 3 THEN 'User Validator'
                        ELSE 'Unknown'
                    END AS user_role
                "),
                'p.program_title',
                'u.created_at'
            )
            ->get();
        
        return response()->json($query);
    }

    public function getRegionCode()
    {
        $query = DB::table('geo_map')
            ->select(
                'geo_code', 'phcode_reg', 'iso_reg', 'reg_code', 'reg_shortname', 'reg_name', 'phcode_prov', 'iso_prv', 'prov_code', 'prov_name', 'phcode_mun', 'dist_code', 'district', 'mun_code', 'mun_name', 'phcode_bgy', 'bgy_code', 'bgy_name', 'lat', 'long'
            )
            ->get();
        return response()->json($query);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request){

      
       $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password'=> 'required|string|min:8',
            'company_name' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        
        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        // create tenant
        $tenant  =  Tenant::create([
            'name' => $request->company_name,
            'owner_id' => $user->id
        ]);

    
      
        // Attach user to tenant
        $tenant->users()->attach($user->id, [
            'role' => 'owner'
        ]);

        
        // create api token
        $token = $user->createToken('auth_token')->plainTextToken;


        

        return response()->json(
            ['message' => 'User registered successfully',
            'token' => $token,
            'tenant' => $tenant
        ], 201);
    }

}

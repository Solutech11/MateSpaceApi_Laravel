<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Authentication extends Controller
{
    //
    public function loginFunc(Request $request){

    }

    public function RegisterFunc(Request $request){

        try{
            $request->validate([
                'username'=>['required', 'string','min:3','max:12'],
                'email'=>['required', 'email'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                ]
            ]);

            User::create([
                'name'=>$request->username,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);

            return response()->json(['Access'=> true,'Error'=>false,"saved"=>true]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error messages in JSON format
            return response()->json([
                'Access' => false,
                'Error' => $e->errors() // Get all validation errors
            ], 400);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json([
                'Access' => false,
                'Error' => $e->getMessage()
            ], 500);
        }
        
    }
}

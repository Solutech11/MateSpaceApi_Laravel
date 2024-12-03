<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Mail\SendEmailVerify;
use App\Mail\SendOTP;
use App\Mail\WelcomeMail;
use App\Models\AuthModel;
use App\Models\UserModel;
use App\Models\Verif;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Authentication extends Controller
{
    //
    public function loginFunc(Request $request){
        try{
            $request->validate([
                'email'=>['required', 'email'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                ]
            ]);

            $user=UserModel::where([
                'email'=>$request->email
            ])->with('userDetails')->with('userPhoto')->with('userQuestions')->first();

            if(!$user) return response()->json([
                "Access"=>true, "Error"=>"Email not found"
            ],400);

            if(!Hash::check($request->password,$user->password)) return response()->json([
                "Access"=>true, "Error"=>"user not found"
            ],400);

            
            if($user->verified==false) return response()->json([
                "Access"=>true, "Error"=>"Email not verified", "Verified"=>false
            ],400);

            $token = Str::random(60);

            AuthModel::updateOrCreate(
                ['user_id' => $user->id], // Match existing verification by user_id
                [
                    'user_id' => $user->id,
                    'auth' => $token,
                ]
            );


            return response()->json(['Access'=> true,'Error'=>false, "Verified"=>true, "Data"=>$user ,"Token"=>$token]);
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

            UserModel::create([
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);

            Mail::to($request->email)->send(new WelcomeMail($request->username));

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

    public function SendVerificationOTPFunc (Request $request){
        try{
            $user= UserModel::where('email',$request->email)->first();

            if(!$user) return response()->json([
                "Access"=>true, "Error"=>"Email not found"
            ],404);

            //otp creation
            $otp = mt_rand(1000, 9999);

            //create verif
            Verif::updateOrCreate(
                ['user_id' => $user->id], // Match existing verification by user_id
                [
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5), // Expiry time
                ]
            );

            Mail::to($user->email)->send(new SendOTP($otp, $user->username));

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

    public function verifyEmail (Request $request){
        try{
            $user= UserModel::where('email',$request->email)->with('userVerif')->first();

            if(!$user) return response()->json([
                "Access"=>true, "Error"=>"Email not found"
            ],404);

            if($user->verified==true) return response()->json([
                "Access"=>true, "Error"=>"User already verified"
            ],400);

            if($user->userVerif->otp!=$request->input(('otp')))return response()->json([
                "Access"=>true, "Error"=>"Invalid Otp"
            ],400);

            //update user
            $user->verified=true;
            $user->save();

            $user->userVerif()->delete();


            response()->json(['Access'=> true,'Error'=>false,"saved"=>true]);
        
            Mail::to($user->email)->send(new SendEmailVerify($user->username));

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

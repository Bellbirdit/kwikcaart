<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Product;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Session;
use Illuminate\Support\Facades\Http;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            // 'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
        }


        // $username = Str::slug('AR') . (User::max('id') + random_int(99, 99999999));
        $otp = $this->generateUniqueOtp();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->otp = $otp;
        $user->type= '3';
        $user->save();
        $usr = User::where('id', $user->id)->first();
        $userId = Crypt::encryptString($usr->id);
        $role = Role::where('name', 'user')->first();
        $usr->assignRole($role);


        $usr = User::where('id', $user->id)->first();
        $userId = $usr->id;

        // $userId = Crypt::encryptString($usr->id);

        // $role = Role::where('name', 'User')->first();
        // $usr->assignRole($role);

        //notification
        // $us = User::where('type', 1)->get();
        // $noti = User::where('id', $user->id)->first();
        // Notification::send($us, new userRegisteredrNotification($noti));
        $fromEmail = config('app.from_email');
        $fromApp = config('app.name');
        Mail::send('email.account-verification', ['user' => $usr, 'otp' => $otp], function ($message) use ($usr, $fromEmail, $fromApp) {
            $message->to($usr->email)
                ->from($fromEmail, $fromApp)
                ->subject("Account verification Code");
        });
        return response()->json([
            'status' => 'success',
            'msg' => 'User Account Created Successfully',
            'userId' => $userId
        ], 200);
    }
    
    public function emailResend(Request $request)
    {


        $usr = User::find($request->id);

        if ($usr) {
            $otp = $usr->otp;
            $fromEmail = config('app.from_email');
            $fromApp = config('app.name');
            Mail::send('email.account-verification', ['user' => $usr, 'otp' => $otp], function ($message) use ($usr, $fromEmail, $fromApp) {
                $message->to($usr->email)
                    ->from($fromEmail, $fromApp)
                    ->subject("Account verification Code");
            });
        }
        return response()->json([
            'status' => 'success',
            'msg' => 'Email sent'

        ], 200);
    }

    public function generateUniqueOtp()
    {
        do {
            $code = random_int(100000, 999999);
            $code = substr($code, 0, 6);
        } while (User::where('otp', '=', $code)->first());
        return $code;
    }

    public function verificationPage($id)
    {
        $user = User::find($id);
        if ($user) {
            Auth::login($user);
            $usr = Auth::user();
            $responseArray = $usr->createToken('abc')->accessToken;
            return view('frontend.auth.code-verification', ['user' => $user]);
        } else {
            abort('404');
        }
    }

    public function VerifyAccount($id, Request $request)
    {
        $user = User::find($id);
        $code = $request->verifyCode;
        if ($user->otp == $code) {
            $user->status = "active";
            $user->save();
            $usr = User::find($user->id);
            // $usr->email=$usr->email;
            // $usr->password= $usr->password;
            // $usr->save();
            $users = User::where('email', $usr->email)->first();
            if ($users) {
                Auth::login($users);
                return response()->json(['status' => 'success', 'msg' => 'Account verified']);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'not login']);
            }
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'You Entered invalid code!']);
        }
    }

    public function login(Request $request)
    {
        $store_id = Session::get('store_id');
        $validator=Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required',

        ]);
        if($validator->fails()){
            return response()->json(['status'=>'fail','msg'=>$validator->errors()->all()]);
        }
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $type = Auth::user()->type;
                $user = Auth::user();
                if($user->status == "active"){
                    Auth::user()->save();
                    $responseArray = $user->createToken('app')->accessToken;
                    return response()->json(['status' => 'success', 'type' => $type, 'user' => $user, 'token' => $responseArray, 'msg' => 'You have successfully login']);    
                }else{
                    Auth::logout();
                    return response()->json(['status'=>'fail','msg'=>"Inactive user found!"]);        
                }
                // Auth::user()->last_login = Carbon::now();
                
            }        }catch(\Exception $exception ){
            return response()->json([
                'status'=>'server',
                'msg'=>$exception->getMessage()
            ],400);
        }
       
             
        return response()->json([
            'status'=>'fail',
            'msg'=>'Invalid Email/Password'
        ]);
        
    }
    public function loginForm(){
        if (Auth::check()){
            Auth::logout();
            return view('frontend.auth.login');
        }else{
            return view('frontend.auth.login');
        }

    }
    //google 
    public function redirectToGoogle()
    {

        return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleGoogleCallback()
    {

        try {
      
            $user = Socialite::driver('google')->stateless()->user();
       
            $finduser = User::where('google_id', $user->id)->first();
       
            if($finduser){
       
                Auth::login($finduser);
      
                return redirect()->intended('/');
       
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'type'=>3,
                    'status'=>'active',
                    
                    
                ]);
                if($newUser){
                    $usr = User::where('id', $newUser->id)->first();
                    $userId = Crypt::encryptString($usr->id);
                    $role = Role::where('name', 'User')->first();
                    $usr->assignRole($role);
                }
                Auth::login($newUser);
      
                return redirect()->intended('/');
            }
      
        } catch (\Exception $e) {
            if ($e->errorInfo[1] === 1062) {
                $msg = "Email is already taken";
            }else{
                $msg = $e->getMessage();
            }
            session()->put('login_error',$msg);
            return redirect('/login');
        }
       
    }
     public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function loginWithFacebook()
    {
        try {
    
            $user = Socialite::driver('facebook')->user();
            $isUser = User::where('fb_id', $user->id)->first();
     
            if($isUser){
                Auth::login($isUser);
                return redirect('/');
            }else{
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'fb_id' => $user->id,
                    'status'=>'active',
                    'type'=>'3'
                ]);
    
                Auth::login($createUser);
                return redirect('/');
            }
    
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
    public function submitForgetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
        }
        $token = Str::random(64);
        $currentDateTime = Carbon::now();
        $newDateTime = Carbon::now()->addHours(2);
        //$newDateTime = $newDateTime->format('Y-m-d H:i:s');

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'expire_at' => $newDateTime,
            'created_at' => $currentDateTime
        ]);
        
        $fromEmail = config('app.from_email');
        $fromApp = config('app.name');
        Mail::send('email.reset-password', ['token' => $token], function ($message) use ($request, $fromEmail, $fromApp) {
            $message->to($request->email);
            $message->subject('Reset Password');
            $message->from($fromEmail, $fromApp);
        });

        return response()->json(['status' => 'success', 'msg' => 'We have emailed your password reset link, please checkout your mail']);
    }
    public function showResetPasswordForm($token)
    {
        $resetToken = DB::table('password_resets')
            ->where([
                'token' => $token
            ])
            ->first();
        if (!empty($resetToken)) {
            $currentDateTime = Carbon::now();
            if ($resetToken->expire_at < $currentDateTime) {
                //$resetToken->delete();
                return view('frontend.auth.token_expired');
            }
            return view('frontend.auth.reset_password', ['token' => $token]);
        } else {

            abort('404');
        }
    }
    public function submitResetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
        }

        $updatePassword = DB::table('password_resets')
            ->where([
                'token' => $request->token
            ])
            ->first();

        if (empty($updatePassword)) {

            return response()->json(['status' => 'fail', 'msg' => 'Invalid token']);
        } else {

            $user = User::where('email', $updatePassword->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();


            return response()->json(['status' => 'success', 'msg' => 'Your password has been changed']);
        }
    }

    // change passsword
    public function changePassword(Request $request){
        $validator=Validator::make($request->all(),[
            'current_password'=>'required',
            'new_password'=>'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password'=>'min:8',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>'fail','msg'=>$validator->errors()->all()]);
        }
        $user=User::where('id',auth()->user()->id)->first();
        if ($user){
            if (Hash::check($request->get('current_password'), Auth::user()->password)) {
                $user->password = bcrypt($request->new_password);
                $user->save();
                return response()->json(['status' => 'success', 'msg' => 'You have successfully change your password']);
            }
            return response()->json(['status'=>'fail','msg'=>'Current password didnt match']);
        }

    }
    // user address
    public function addAddress(Request $request){
          
    
        $addres = new UserAddress();
        $addres->address = $request->address;
        $addres ->address_type = $request->address_type;
        $addres ->building_name = $request->building_name;
        $addres ->flat_name = $request->flat_name;
        $addres->user_id = Auth::user()->id;
        $addres->save();
        if ($addres){
    
            return response()->json(['status'=>'success','msg'=>'Address added successfully']);
    
        }else{
    
            return response()->json(['status'=>'fail','msg'=>'FAILED! try again']);
    
        }
    }
    public function deleteAddress ($id)
    {
         $addres=UserAddress::find($id);
          $addres->delete();
        if ($addres){
            return response()->json(['status'=>'success','msg'=>'Address is deleted']);
        }
        return response()->json(['status'=>'fail','msg'=>'failed to delete address']);
    }


public function sendOtps(Request $request)
{
    try{
        $contact = $request->get('contact');
        $otp = rand(100000, 999999);
        $message = "Dear user, your Safeer OTP is $otp.";
        $response = Http::post('http://162.214.103.200:6005/api/v2/SendSMS', [
            'ApiKey' => 'Saf0922',
            'ClientId' => 'Safeer.Group',
            'SenderId' => 'Safeermrket',
            'MobileNumbers' => $contact,
            'Message' => $message,
            'Is_Unicode' => false,
            'Is_Flash' => false,
        ]);
    
        if ($response->ok()) {
            Log::info("OTP sent to {$contact}");
            return response()->json(['message' => 'OTP sent successfully']);
        } else {
            $error = "Failed to send OTP to {$contact}. Response code: {$response->status()}";
            Log::error($error);
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }    
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
    
}


     public function sendOtp(Request $request)
    {
            $verificationCode = rand(100000, 999999);
            //$validate->delete();
            $register = new User();
            $register->contact = $request->contact;
            $register->otp = $verificationCode;
            // dd($register);
            // $register->save();

            $apiKey = urlencode('Saf0922');
            $numbers = array($request->contact);
            $numbers = implode(',', $numbers);
            $client = ('Safeer.Group');
            $sender = urlencode('Safeermrket');
            $message = rawurlencode('Your Kwikcaart OTP is: 3456');
            $data = array('ApiKey' => $apiKey, 'MobileNumbers' => $numbers,'ClientId'=>$client,'SenderId' => $sender, 'message' => $message);
           
            $ch = curl_init('http://162.214.103.200:6005/api/v2/SendSMS');
           
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            
            if ($response === false) {
                $error_message = curl_error($ch);
                 dd($error_message);
                echo "cURL error: " . $error_message;
            }
            curl_close($ch);

            var_dump($response);
            echo $response;

     
        
    }
}






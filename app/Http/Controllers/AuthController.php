<?php

namespace App\Http\Controllers;

use App\Events\NewUserJoin;
use App\Events\Registerion;
use App\Http\Requests\RegisterRequest;
use App\Mail\verifyMail;
use App\Models\User;
use App\Notifications\NewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {

        $otp = rand(100000, 999999);
        $expireOtp = now()->addMinutes(5);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expire' => $expireOtp
        ]);
        Mail::to($user->email)->send(new verifyMail($user->email, $otp));


        $users = User::where('id', '!=', $user->id)->get();


        // event(new Registerion($users , $user)) ;

        Registerion::dispatch($users , $user) ;
        // Notification::sendNow($users , new NewUser($user)) ;
        return redirect()->route('verifyView', ['id' => $user->id]);
    }

    public function verifyView($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('registerView')->withErrors(['email' => 'Invalid user.']);
        }

        return view('auth.verify-email', compact('user'));
    }

    public function verify(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('registerView')->withErrors(['email' => 'Invalid user.']);
        }

        $validate = Validator::make($request->all(), [
            'otp' => 'required|numeric|min:5',
        ], [
            'otp.required' => 'OTP is required',
            'otp.min' => 'OTP must be at least 5 characters',

        ]);
        if ($validate->fails()) {
            return redirect()->route('verifyView', ['id' => $user->id])->withErrors($validate)->withInput();
        }
        $otp = $request->input('otp');
        $expireOtp = $user->otp_expire;
        if ($otp == $user->otp) {
            if ($expireOtp > now()) {
                $user->email_verified_at = now();

                $user->save();
                return redirect()->route('regiserView')->with('success', 'OTP verified successfully.');
            } else {
                return redirect()->route('verifyView', ['id' => $user->id])->withErrors(['otp' => 'OTP expired.']);
            }
        } else {
            return redirect()->route('verifyView', ['id' => $id])->withErrors(['otp' => 'Invalid OTP.']);
        }
    }

    public function resendOtp($id)
    {

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('registerView')->withErrors(['email' => 'Invalid user.']);
        }
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expire = now()->addMinutes(5);
        $user->save();
        Mail::to($user->email)->send(new verifyMail($user->email, $otp));
        return redirect()->route('verifyView', ['id' => $user->id])->with('success', 'OTP sent successfully.');
    }


    public function loginView()
    {
        return view('auth.login');
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'identifier' => 'required',
            'password' => 'required|min:6',
        ], [
            'identifier.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',

        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $user = User::where('email', $request->identifier)->orWhere('phone', $request->identifier)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('user', $user);
                Auth::login($user);

                return redirect()->route('home' );
            } else {
                return redirect()->back()->withErrors(['password' => 'Invalid password.']);
            }
        } else {
            return redirect()->back()->withErrors(['identifier' => 'Invalid email or phone.']);
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('user');
        return redirect()->route('loginView');
    }

    public function resetPassEnterEmail(){
        return view('auth.resetPassword');
    }

    public function resetPassword(Request $request )
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.exists' => 'Email is not registered',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $user = User::where('email' , $request->email)->first();

        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expire = now()->addMinutes(5);
        $user->save();
        Mail::to($user->email)->send(new verifyMail($user->email, $otp));
        return redirect()->route('passwordOtp' , ['id' => $user->id]) ;
    }

    public function passwordOtp($id){
        $user = User::findOrFail($id)->first() ;
        return view('auth.passwordOtp' , ['user' => $user]);
    }


    public function verifyOtpPassword(Request $request , $id)
    {
        $validate = Validator::make($request->all(), [
            'otp' => 'required',
        ], [
            'otp.required' => 'OTP is required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $user = User::findOrFail($id);
        $expireOtp = $user->otp_expire;
        if ($user->otp == $request->otp) {
            if ($expireOtp > now()) {
                return redirect()->route('resetPasswordForm' , ['id'=> $id]);
            } else {
                return redirect()->back()->withErrors(['otp' => 'Expire OTP.']);
            }
        } else {
            return redirect()->back()->withErrors(['otp' => 'Invalid OTP.']);
        }
    }

    public function NewPasswordForm($id){
        $user = User::findOrFail($id)->first() ;
        return view('auth.resetPasswordForm' , ['user' => $user]);
    }

    public function resetPasswordForm(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ], [
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters.',
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.same' => 'Password confirmation must be same as password.',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('loginView')->with('success', 'Password reset successfully.');
    }
}

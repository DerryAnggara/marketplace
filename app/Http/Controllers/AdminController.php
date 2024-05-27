<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use constGuards;
use constDefaults;
use Illuminate\Support\Facades\File;



class AdminController extends Controller
{
    public function loginHandler(Request $request){
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if($fieldType == 'email'){
            $request->validate([
                'login_id'=>'required|email|exists:admins,email',
                'password'=>'required|min:5|max:45'
            ],[
                'login_id.required'=>'Email or Username is required',
                'login_id.email'=>'Invalid email address',
                'login_id.exists'=>'Email is not exists in system',
                'password.required'=>'Password is required'
            ]);
        }else{
            $request->validate([
                'login_id'=>'required|exists:admins,username',
                'password'=>'required|min:5|max:45'
            ],[
                'login_id.required'=>'Email or Username is required',
                'login_id.exists'=>'Username is not exists in system',
                'password.required'=>'Password is required'
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password'=>$request->password
        );

        if( Auth::guard('admin')->attempt($creds) ){
            return redirect()->route('admin.home');
        }else {
            return redirect()->route('admin.login')->withInput()->with('fail','Incorrect password');
        }
    }

    public function logoutHandler(Request $request){
        Auth::guard('admin')->logout();
        session()->flash('fail','you are logged out!');
        return redirect()->route('admin.login');
    }

    public function sendPasswordResetLink(Request $request){
        $request->validate([
            'email'=>'required|exists:admins,email'
        ],[
            'email.required'=>'The :attibute is required',
            'email.email'=>'Invalid email address',
            'email.exists'=>'The :attribute is not exists in system'
        ]);

        //get admin details
        $admin = Admin::where('email',$request->email)->first();

        //generate tokens
        $token = base64_encode(Str::random(64));

        //check
        $oldToken = DB::table('password_reset_tokens')
                     ->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])
                     ->first();

        if( $oldToken ){
            //update token
            DB::table('password_reset_tokens')
             ->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])
             ->update([
                'token'=>$token,
                'created_at'=>Carbon::now()
             ]);
        }else{
            //add new token
            DB::table('password_reset_tokens')->insert([
                'email'=>$request->email,
                'guard'=>constGuards::ADMIN,
                'token'=>$token,
                'created_at'=>Carbon::now()
            ]);
        }

        $actionLink = route('admin.reset-password',['token'=>$token,'email'=>$request->email]);

        $data = array(
            'actionLink'=>$actionLink,
            'admin'=>$admin
        );

        $mail_body = view('email-templates.admin-forgot-email-template',$data)->render();

        $mailConfig = array(
            'mail_from_email'=>env('EMAIL_FROM_ADDRESS'),
            'mail_from_name'=>env('EMAIL_FROM_NAME'),
            'mail_recipient_email'=>$admin->email,
            'mail_recipient_name'=>$admin->name,
            'mail_subject'=>'Reset password',
            'mail_body'=>$mail_body

        );

        if( sendEmail($mailConfig)){
            session()->flash('success', 'yeay');
            return redirect()->route('admin.forgot-password');
        }else{
            session()->flash('fail', 'not send');
            return redirect()->route('admin.forgot-password');
        }
    }
    public function profilView(Request $request){
        $admin = null;
        if(Auth::guard('admin')->check() ){
            $admin = Admin::findOrfail(auth()->id());
        }
        return view('back.pages.admin.profile', compact('admin'));
    }

    public function changeProfilePicture(Request $request){
        $admin = Admin::findOrFail(auth('admin')->id());
        $path = 'images/users/admins/';
        $file = $request->file('adminProfilePictureFile');
        $old_picture = $admin->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $filename = 'ADMIN_IMG'.rand(2,1000).$admin->id.time().uniqid().'.jpg';

        $upload = $file->move(public_path($path),$filename);

        if($upload){
            if($old_picture != null && File::exists(public_path($path.$old_picture))){
                File::delete(public_path($path.$old_picture));
            }
            $admin->update(['picture'=>$filename]);
            return response()->json(['status'=>1,'msg'=>'your profile picture has been updated']);
        }else{
            return response()->json(['status'=>0,'msg'=>'something went wrong']);
        }
    }

    public function changeLogo(Request $request){
        $path = 'images/site/';
        $file = $request->file('site_logo');
        $settings = new GeneralSetting();
        $old_logo = $settings->first()->site_logo;
        $file_path = $path.$old_logo;
        $filename = 'LOGO_'.uniqid().'.'.$file->getClientOriginalExtension();

        $upload = $file->move(public_path($path),$filename);

        if($upload){
            if( $old_logo != null && File::exists(public_path($file_path))){
                File::delete(public_path($file_path));
            }
            $settings = $settings->first();
            $settings->site_logo = $filename;
            $update = $settings->save();

            return response()->json(['status'=>1,'msg'=>'site logo has been updated']);
        }else{
            return response()->json(['status'=>0,'msg'=>'something went wrong']);
        }
    }

    public function changeFavicon(Request $request){
        $path = 'images/site/';
        $file = $request->file('site_favicon');
        $settings = new GeneralSetting();
        $old_favicon = $settings->first()->site_favicon;
        $file_path = $path.$old_favicon;
        $filename = 'FAV_'.uniqid().'.'.$file->getClientOriginalExtension();

        $upload = $file->move(public_path($path),$filename);

        if($upload){
            if( $old_favicon != null && File::exists(public_path($file_path))){
                File::delete(public_path($file_path));
            }
            $settings = $settings->first();
            $settings->site_favicon = $filename;
            $update = $settings->save();

            return response()->json(['status'=>1,'msg'=>'site favicon has been updated']);
        }else{
            return response()->json(['status'=>0,'msg'=>'something went wrong']);
        }

    }
}

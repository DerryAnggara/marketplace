<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;
use App\Models\VerificationToken;
use Illuminate\Support\Facades\DB;
use constGuards;
use constDefaults;
use Illuminate\Support\Facades\File;
use Mberecall\Kropify\Kropify;
use App\Models\Shop;

class SellerController extends Controller
{
    public function login(Request $request){
        $data = [
            'pageTitle'=>'Seller login'
        ];
        return view('back.pages.seller.auth.login',$data);
    }

    public function register(Request $request){
        $data = [
            'pageTitle'=>'create seller account'
        ];
        return view('back.pages.seller.auth.register',$data);
    }

    public function home(Request $request){
        $data = [
            'pageTitle'=>'Seller dashboard'
        ];
        return view('back.pages.seller.home',$data);
    }

    public function createSeller(Request $request){
        //validate
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:sellers',
            'password'=>'min:5|required_with:confirm_password|same:confirm_password',
            'confirm_password'=>'min:5'
        ]);
        $seller = new Seller();
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->password = Hash::make($request->password);
        $saved = $seller->save();

        if($saved){
            //generate token
            $token = base64_encode(Str::random(64));

            VerificationToken::create([
                'user_type'=>'seller',
                'email'=>$request->email,
                'token'=>$token
            ]);

            $actionLink = route('seller.verify',['token'=>$token]);
            $data['action_link'] = $actionLink;
            $data['seller_name'] = $request->name;
            $data['seller_email'] = $request->email;

            //send activation link
            $mail_body = view('email-templates.seller-verify-template',$data)->render();

            $mailConfig = array(
                'mail_from_email'=>env('EMAIL_FROM_ADDRES'),
                'mail_from_name'=>env('EMAIL_FROM_NAME'),
                'mail_recipient_email'=>$request->email,
                'mail_recipient_name'=>$request->name,
                'mail_subject'=>'verify seller account',
                'mail_body'=>$mail_body
            );
            if( sendEmail($mailConfig)){
                return redirect()->route('seller.register-success');
            }else{
                return redirect()->route('seller.register')->with('fail','something went wrong while sending');
            }

        }else{
            return redirect()->route('seller.register')->with('fail','something went wrong');
        }
    }

    public function registerSuccess(Request $request){
        return view('back.pages.seller.register-success');
    }

    public function loginHandler(Request $request){
        $fieldtype = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if($fieldtype == 'email'){
            $request->validate([
                'login_id'=>'required|email|exists:sellers,email',
                'password'=>'required|min:5|max:45'
            ],[
                'login_id.required'=>'Email or username is required',
                'login_id.email'=>'Invalid email addres',
                'login_id.exists'=>'Email not exists in system',
                'password.required'=>'Password is required'
            ]);
        }else{
            $request->validate([
                'login_id'=>'required|exists:sellers,username',
                'password'=>'required|min:5|max:45'
            ],[
                'login_id.required'=>'Email or username is required',
                'login_id.exists'=>'Email not exists in system',
                'password.required'=>'Password is required'
            ]);
        }
         $creds = array(
            $fieldtype =>$request->login_id,
            'password'=>$request->password
         );

         if(Auth::guard('seller')->attempt($creds)){
            return redirect()->route('seller.home')->with('success','you are loged in');
         }else{
            return redirect()->route('seller.login')->withInput()->with('fail','Incorrect password');
         }
    }
    public function logoutHandler(Request $request){
        Auth::guard('seller')->logout();
        return redirect()->route('seller.login')->with('fail','you are loged out');
    }

    public function profileView(Request $request){
        $data = [
            'pageTitle'=>'profile'
        ];
        return view('back.pages.seller.profile',$data);
    }


    public function changeProfilePicture(Request $request){
        $seller = Seller::findOrFail(auth('seller')->id());
        $path = 'images/users/sellers/';
        $file = $request->file('sellerProfilePictureFile');
        $old_picture = $seller->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $filename = 'seller_IMG'.rand(2,1000).$seller->id.time().uniqid().'.jpg';
        $upload = $file->move(public_path($path),$filename);

        if($upload){
            if($old_picture != null && File::exists(public_path($file_path))){
                File::delete(public_path($file_path));
            }
            $seller->update(['picture'=>$filename]);
            return response()->json(['status'=>1,'msg'=>'your profile picture has been updated']);
        }else{
            return response()->json(['status'=>0,'msg'=>'something went wrong']);
        }
    }

    public function shopSettings(Request $request){
        $seller = Seller::findOrFail(auth('seller')->id());
        $shop = Shop::where('seller_id',$seller->id)->first();
        $shopInfo = '';

        if(!$shop){
            //create shopfor this seller when not exists
            Shop::create(['seller_id'=>$seller->id]);
            $nshop = Shop::where('seller_id',$seller->id)->first();
            $shopInfo = $nshop;
        }else{
            $shopInfo = $shop;
        }

        $data = [
            'pageTitle'=>'shop settings',
            'shopInfo'=>$shopInfo
        ];

        return view('back.pages.seller.shop-settings',$data);
    }

    public function shopSetup(Request $request){
        $seller = Seller::findOrFail(auth('seller')->id());
        $shop = Shop::where('seller_id',$seller->id)->first();
        $old_logo_name = $shop->shop_logo;
        $logo_name = '';
        $path = 'images/shop/';

        //validate form
        $request->validate([
            'shop_name'=>'required|unique:shops,shop_name,'.$shop->id,
            'shop_phone'=>'required|numeric',
            'shop_address'=>'required',
            'shop_description'=>'required',
            'shop_logo'=>'nullable|mimes:jpeg,png,jpg'
        ]);

        if( $request->hasFile('shop_logo')){
            $file = $request->file('shop_logo');
            $filename = 'SHOPLOGO_'.$seller->id.uniqid().'.'.
            $file->getClientOriginalExtension();

            $upload = $file->move(public_path($path),$filename);

            if($upload){
                $logo_name = $filename;

                //delete an existing shop logo
                if($old_logo_name != null && File::exists(public_path($path.$old_logo_name))){
                    File::delete(public_path($path.$old_logo_name));
                }
            }
        }

        //update seller shop details
        $data = array(
            'shop_name'=>$request->shop_name,
            'shop_phone'=>$request->shop_phone,
            'shop_address'=>$request->shop_address,
            'shop_description'=>$request->shop_description,
            'shop_logo'=>$logo_name != null ? $logo_name : $old_logo_name
        );

        $update = $shop->update($data);

        if($update){
            return redirect()->route('seller.shop-settings')->with('success','your shop info have been updated');
        }else{
            return redirect()->route('seller.shop-settings')->with('fail','error on updating your shop info');
        }
    }
}

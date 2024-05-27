<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;

class SellerProfile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    public $name, $email, $username, $phone, $address, $picture;
    public $current_password, $new_password, $new_password_confirmation;

    protected $queryString = ['tab'=>['keep'=>true]];

    public function selectTab($tab){
        $this->tab = $tab;
    }
    public function mount(){
        $this->tab = request()->tab ? request()->tab : $this->tabname;

        //populate
        $seller = Seller::findOrFail(auth('seller')->id());
        $this->name = $seller->name;
        $this->email = $seller->email;
        $this->username = $seller->username;
        $this->phone = $seller->phone;
        $this->address = $seller->address;
    }

    public function updateSellerPersonalDetails(){
        //validate
        $this->validate([
            'name'=>'required|min:4',
            'username' => 'nullable|min:4|unique:sellers,username,'.auth('seller')->id(),
        ]);
        $seller = Seller::findOrFail(auth('seller')->id());
        $seller->name = $this->name;
        $seller->username = $this->username;
        $seller->address = $this->address;
        $seller->phone = $this->phone;
        $update = $seller->save();

        if($update){
            $this->dispatch('updateAdminSellerHeaderInfo');
            $this->showToastr('success','personal details have been sucessfuly updated');
        }else{
            $this->showToastr('error','something went wrong');
        }

    }

    public function updatePassword(){
        $seller = Seller::findOrFail(auth('seller')->id());

        //validate the form
        $this->validate([
            'current_password'=>[
                'required',
                function($attribute,$value,$fail) use ($seller){
                    if(!Hash::check($value,$seller->password)){
                        return $fail(__('the curent password is incorrect'));
                    }
                }
            ],
            'new_password'=>'required|min:5|max:45|confirmed'
        ]);

        //updated password
        $query = Seller::findOrFail(auth('seller')->id())->update([
            'password'=>Hash::make($this->new_password)
        ]);

        if($query){
            $this->current_password = $this->new_password = $this->new_password_confirmation = null;
            $this->showToastr('success','yeey');
        }else{
            $this->showToastr('error','ieww');
        }

    } 

    public function showToastr($type, $message){
        return $this->dispatch('showToastr',[
            'type'=>$type,
            'message'=>$message
        ]);
    }

    public function render()
    {
        return view('livewire.seller.seller-profile',[
            'seller'=>Seller::findOrFail(auth('seller')->id())
        ]);
    }
}

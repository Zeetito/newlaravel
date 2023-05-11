<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //
    public function  storeAvatar(Request $request){
        $request->validate([
            'avatar'=>'required|image|max:5000'
        ]);
            $user = auth()->user();

            $filename = $user->id."-".uniqid().".jpg";

        $imgData = Image::make($request->file('avatar'))->fit(200)->encode('jpg');
        Storage::put('public/avatars/'.$filename ,$imgData);

        $oldAvatar = $user->avatar;

        $user->avatar=$filename;
        $user->save();
        
        // $request->file('avatar')->store('public/avatars');
        if($oldAvatar != "/fallback-avatar.jpg"){
            Storage::delete(Str_replace("/storage/","public/",$oldAvatar));
            return back()->with("success","New Avatar Uploaded Successfully");
        }
        return"Successfylly Added";
    }
    
    public function ShowAvatarForm(User $user){
        return view('avatar-form');
    }

    public function profile(User $user){
        // $thePost = $user->posts()->get();
        // return $thePost;
        return view('profile-posts',['user'=>$user, 'posts'=>$user->posts()->latest()->get()]); 
    }

    public function register(Request $request){
        $incomingFields = $request->validate([
            'username'=>['required','min:3','max:20', Rule::unique('users','username')],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','min:8','confirmed'],
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success','You have successfully created an account');
    }

    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginusername'=>'required', 
            'loginpassword'=>'required',
        ]);
        if(auth()->attempt(['username'=>$incomingFields['loginusername'], 'password'=>$incomingFields['loginpassword'] ])){
            $request->session()->regenerate();
                return redirect('/')->with('success','You have successfully logged in');
        }else{
                return redirect('/')->with('failure','password or username incorrect');
        }
    }

    public function ShowCorrectHomepage(){
        if(auth()->check()){
            return view('homepage-feed');
        }else{
            return view('homepage');
        }
    }

    public function logout(){
        auth()->logout();
        return redirect('/')->with('success','You are now logged out');
        
    }
}

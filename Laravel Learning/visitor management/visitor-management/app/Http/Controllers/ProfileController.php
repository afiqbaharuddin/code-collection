<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index(){
        $data = User::findOrFail(Auth::user()->id);
        return view('profile',compact('data'));
    }

    function edit_validation(Request $request){

        $request->validate([
            'email' => 'required|email',
            'name'  => 'required',
        ]);

        $data = $request->all();

        if(!empty($data['password'])){
            $form_data = array(
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['[password']),
            );
        }else{
            $form_data = array(
                'name'      => $data['name'],
                'email'     => $data['email'],
            );
        }

        User::whereId(Auth::user()->id)->update($form_data);
        return redirect('profile')->with('success', 'Profile Data Updated');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {

    }

    public function authenticate(Request $request)
    {
        $data = $request->json()->get('data');
        $validator = Validator::make(
            $data,
            [
                'username'=>'string|required|',
                'password'=>'string|required',
                ]
        );
        if($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }
        // $credentials = array('username'=>$data['username'],'password'=>$data['password']);
        $user = User::where('username',$data['username'])->first();
        if($user && Hash::check($data['password'],$user->password)){
            $api_key = base64_encode(random_bytes(40));
            return $this->get_response(['data'=>['api_key'=>$api_key]]);
        } else {
            $validator->getMessageBag()->add('username','Wrong Username or Password.');
            return $this->respondWithErrorMessage($validator);
        }
    }
}
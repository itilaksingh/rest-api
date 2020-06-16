<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User; 
use App\ProviderProfilesModel; 
use Validator;

class UserController extends Controller
{
    //for login
    public function login(Request $request){ 

        $validator = Validator::make($request->all(),[            
            'email' => 'required|email',             
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyAccessToken')->accessToken; 
            return response()->json(['success' => $success], 200); 
        } 
        else{ 
            return response()->json(['error'=>'Invalid credentials, Please check and try again. '], 401); 
        } 
    }

    //for registration

    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(),[ 
            'name' => 'required', 
            'email' => 'required|email|unique:users,email', 
            'mobile_number' => 'required|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|min:10', 
            'latitude' => 'required', 
            'longitude' => 'required', 
            'role' => 'required', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
                $request_data= $request->all();
                $request_data['password'] = Hash::make($request_data['password']);
                $user = User::create($request_data); 

                if($user->role==1){
                    $profile_data=array(
                        'user_id_fk'=>$user->id,
                        'name'=>$user->name,
                        'latitude'=>$user->latitude,
                        'longitude'=>$user->longitude,
                    );
                    ProviderProfilesModel::create($profile_data);
                }                


                $success['token'] =  $user->createToken('MyAccessToken')->accessToken; 
                $success['name'] =  $user->name;

        return response()->json(['success'=>$success], 200); 
    }

    public function profile(Request $request)
    {
        $user = Auth::user(); 
        if($user->role==1){
            $user->provider=ProviderProfilesModel::where('user_id_fk', '=', $user->id)->get();    
        }
        return response()->json(['success' => $user], 200); 
        
    }



    public function update(Request $request)
    {
        return $request->all();
        
    }




}

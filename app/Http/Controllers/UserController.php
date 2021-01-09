<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
class UserController extends Controller 
{
	public $successStatus = 200;

	// Login
    public function login(Request $request)
    { 

    	$validator = Validator::make($request->all(), [ 
            'email'=>'required',
            'password'=> 'required',
        ]);
        if($validator->fails())
        {
        	return response()->json(['error'=>$validator->errors()],401);
        }
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){ 
            $user = Auth::user(); 
            $success['user'] =  $user; 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus);
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised Access'], 401); 
        } 
    }
    // Register new user
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:users,email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
		if ($validator->fails()) 
		{ 
            return response()->json(['error'=>$validator->errors()], 401);            
		}
		$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
		return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
}
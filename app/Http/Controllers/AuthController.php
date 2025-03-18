<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Illuminate\Support\Str;
use App\Http\Resources\TaskResource;



class AuthController extends Controller
   
   {
    use GeneralTrait;


    public function register(Request $request)
   
    {
   
     $validate = Validator::make($request->all(),[
       'name' => 'required|string',
       'email' => 'required|email|string|unique:users,email',
       'password' => 'required|string'
       ]);
       if($validate->fails()){
        return $this->requiredField($validate->errors());    
        }
       
      try{
       $data=$request->all();
               
       $user=User::create($data);
       $token = $user->createToken('apiToken')->accessToken;

       return response()->json([
        'message' => 'User created successfully',
        '$res' => [
          'user' =>UserResource::make($user),
          'token' => $token
       ],
       ], 201);
    
       
   
    }
    catch(\Exception $ex){
           return $this->apiResponse($data,false,$ex->getMessage(),500);
   
          }
   
   }
   


  
    public function login(Request $request)
    
     {
      $validate = Validator::make($request->all(),[
        'email' => 'required|email|string',
        'password' => 'required|string'
        ]);
        if($validate->fails()){
        return $this->requiredField($validate->errors()->first());    
        }

        try{
 
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
          return $this->unAuthorizeResponse('incorrrect username or password');
        }
  
        
        $token= $user->createToken($request->email)->accessToken;
        $res = [
        'user' =>UserResource::make($user),
        'token' => $token
         ];
         
         return response()->json([
        'message' => 'User created successfully',
        '$res' => [
          'user' =>UserResource::make($user),
          'token' => $token
       ],
       ], 200);

        }
      catch(\Exception $ex){
        return $this->apiResponse($user,false,$ex->getMessage(),500);

       }

    }



    public function logout(Request $request)
       
     {
      try{
       auth('api')->user()->tokens()->delete();
       return $this->apiResponse('user logged out');
      }catch(\Exception $ex){
        return $this->apiResponse(null,false,$ex->getMessage(),500);

       }

      }


      public function getTasks()
    {

      $id=Auth::id();
      $user = User::findOrFail($id);

      $tasks = $user->tasks()->withTrashed()->get();

      $tasks=TaskResource::collection($tasks);
      return $this->apiResponse($tasks) ;  
     
    }
     

     }

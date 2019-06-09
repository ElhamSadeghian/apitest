<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;

class UsersController extends Controller
{
    public function get()
    {
      $user = User::orderBy('id', 'asc')->paginate(10);
      if(empty($user->all())){
        $data['message'] = ['کاربری ثبت نام نشده است.'];
        return response()->json([
          'status' => 200,
          'errors' => $data,
          'data' => []
        ],200);
      }
      return response()->json([
        'status' => 200,
        'data' => [
          'data' => $user->all(),
          'links' => [
            'count' => $user->count(),
            'currentPage' => $user->currentPage(),
            'firstItem' => $user->firstItem(),
            'lastPage' => $user->lastPage(),
            'onFirstPage' => $user->onFirstPage(),
            'perPage' => $user->perPage(),
            'total' => $user->total(),
          ]
        ]
      ],200);
    }

    public function getOneUser($id)
    {
      $user = User::find($id);
      return response()->json([
        'status' => 200,
        'data' => [
          'data' => $user
        ]
      ], 200);
    }

    public function login(Request $request){
      if(Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
        ])){
  
          $user = Auth::user();
          $success['token'] =  $user->createToken('MyApp')->accessToken;
          return response()->json([
            'status' => 200,
            'success' => [
              'message' => $success
            ]
            ], 200);
      }
      else{
          return response()->json([
            'error'=>'Unauthorised'
          ], 401);
      }
  }

  public function regist(Request $request)
  {
    $validator = \Validator::make($request->all(),[
      'fullName' => 'required|max:255',
      'phone' => 'required',
      'email' => 'required|e-mail',
      'password' => 'required'
    ]);
    if($validator->fails()){
      return response()->json([
        'status' => 406,
        'errors' => $validator->errors()
      ]);
    }

    $pass = $request->password;

    $user = new User($request->all());
    $user->registerDate = time();
    $user->password = \Hash::make($pass);
    
    $success['token'] =  $user->createToken('MyApp')->accessToken;
    $success['fullName'] =  $user->fullName;
    $user->save();
    return response()->json([
      'status' => 200,
      'success' => [
        'message' => $success
      ]
    ],200);
  }

  public function update(Request $request)
  {
    $validator = \Validator::make($request->all(),[
      'fullName' => 'required|max:255',
      'phone' => 'required',
      'email' => 'required|e-mail',
      'birthDate' => 'required',
      'avatar' => 'image',
      'gender' => 'required',
      'zipCode' => 'required'
    ]);
    if($validator->fails()){
      return response()->json([
        'status' => 406,
        'errors' => $validator->errors()
      ]);
    }

    $user = User::find($request->id);
    $user->fullName=$request->fullName;
    $user->phone=$request->phone;
    $user->email=$request->email;
    $user->birthDate=$request->birthDate;
    $user->gender=$request->gender;
    $user->zipCode=$request->zipCode;

    if(!empty($request->password)){
      $user->password = $request->password;
    }


      // var_dump(storage_path('app'.$user->avatar));die;
    if($request->hasfile('avatar')){
      if (file_exists(storage_path('app/'.$user->avatar))) {
          unlink(storage_path('app/'.$user->avatar));
      }
      // var_dump($request->file('avatar')->store('public'));die;
      $user->avatar = $request->file('avatar')->store('public');
    }
      
    $user->save();
    return response()->json([
      'status' => 200,
      'success' => [
        'message' => 'true'
      ]
    ],200);
  }

  public function details()
    {
      if(Auth::check()){
        $user = Auth::user();
        // var_dump($user);die;
        return response()->json([
          'success' => $user
        ], 200);
      }else{
        return response()->json([
          'status' => 401,
          'errors' => 'Not Logged In'
        ]);
      }
    }

    public function login1()
    {
      return response()->json([
        'status' => 401,
        'message' => 'not login'
      ], 401);
    }

  public function delete($id)
  {
    $user = User::find($id);

    //avatar
    if (file_exists(storage_path('app/'.$user->avatar))) {
      unlink(storage_path('app/'.$user->avatar));
    }

    $user->delete();
    return response()->json([
      'status' => 200,
      'success' => [
        'message' => 'true'
      ]
    ],200);

  }

  public function test()
  {
    return response()->json([
      'status' => 200
    ], 200);
  }

 public function curl(){
  $ch = curl_init();
  $curlConfig = array(
      CURLOPT_URL            => "localhost/ApiTest/public/api/user/test",
      CURLOPT_POST           => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS     => array(
          'field1' => 'some date',
          'field2' => 'some other data',
      )
  );
  curl_setopt_array($ch, $curlConfig);
  $result = curl_exec($ch);
  curl_close($ch);
  
  print_r($result);
 }

}

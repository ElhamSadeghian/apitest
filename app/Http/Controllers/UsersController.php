<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
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
    $user->save();
    return response()->json([
      'status' => 200,
      'success' => [
        'message' => 'true'
      ]
    ],200);
  }

  public function edit($id)
  {
    $user = User::find($id);
      return response()->json([
        'data' => $user
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

}

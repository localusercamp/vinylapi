<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $credentials = $request->only(['login', 'password']);

    $errors = validator($credentials, [
      'login' => 'required|string',
      'password' => 'required|string',
    ])->errors();

    if ($errors->isNotEmpty()) abort(422);

    $is_logged = Auth::attempt($credentials);

    if (!$is_logged) abort(422);

    return Auth::user();
  }


  public function logout(Request $request)
  {
    return Auth::logout();
  }



  public function me(Request $request)
  {
    return auth()->user();
  }
}

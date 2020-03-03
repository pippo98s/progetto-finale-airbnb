<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
  public function setAvatar(Request $request, $id){
    $user = User::findOrFail($id);

    $data = $request -> validate([
      'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
    ]);

    $file = $request -> file('avatar') -> store('avatars', 'public');

    $user -> avatar = $file;

    $user -> save();

    return redirect() -> route('home') -> with('avatar_updated', 'Avatar successfully updated!');
  }

  public function userInfo($id){
    if(Auth::id() == $id){
      return view('pages.user-info');
    } else{
      abort(404);
    }
  }
}

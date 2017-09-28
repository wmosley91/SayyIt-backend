<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Auth;

class userController extends Controller
{
    public function __construct()
    {
      $this->middleware('jwt.auth', ['only' => ['']]);
    }

    public function getUsers()
    {

    }

    public function show($id)
    {
      $user = User::where('id', '=', $id)
      ->select('id', 'name', 'bio', 'reputation')
      ->first();
      $tags = UserTag::where('usertags.userID', '=', $id)
      ->join('tags', 'userTags.tagID', '=', 'tags.id')
      ->select('usertags.id', 'tags.name')
      ->orderBy('usertags.id', 'DESC')
      ->get();

      return Response::json(['user' => $user, 'tags' => $tags]);

    }

    public function delete($id)
    {
      $user = User::find($id);
      $user->delete();
    }

    public function ban($id)
    {
      $user = User::find($id);
      $user->ban = 1;
      $user->save();
    }
}

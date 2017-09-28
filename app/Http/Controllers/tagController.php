<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use Purifier;
use Auth;

class tagController extends Controller
{
    public function __construct() {
      $this->middleware('jwt.auth', ['only' => ['store']]);
    }


    public function store(Request $request, $id)
    {
      $rules = [
        'tags' => 'required'
      ];

      $validator = Validator::make(Purifier::clean($request->all()), $rules);

      if($validator->fails()) return Reponse::json(['error' => 'something went wrong (store tag function)']);

      $user = Auth::user();
      $tags = $request->input('tags');

      foreach($tags as $key => $tag)
      {
        $checkTag = Tag::where('name', '=', $tag)->first();
        if(empty($checkTag)) {
          $newTag = new Tag;
          $newTag->name = $tag;
          $newTag->count = 1;
          $newTag->userID = $user->id;
          $newTag->save();

          $userTag = new UserTag;
          $userTag->userID = $id;
          $userTag->taggerID = $user->id;
          $userTag->tagID = $newTag->id;
          $userTag->save();
        }
        else {
          $checkTag->count = $checkTag->count + 1;
          $checkTag->save();

          $userTag = new UserTag;
          $userTag->userID = $id;
          $userTag->taggerID = $user->id;
          $userTag->tagID = $checkTag->id;
          $userTag->save();
        }
    }
    return Response::json(['success' => 'tag applied/created (store tag function)']);
  }

  public function delete($id)
  {
    $tag = Tag::find($id);
    $tags = UserTag::where('tagID', '=', $tag->id)->get();
    foreach($tags as $key => $t)
    {
      $t->delete();
    }
    $tag->delete();
  }

  public function remove($id)
  {
    $tag = UserTag::find($id);
    $tagID = $tag->tagID;
    $t = Tag::where('tagID', '=', $tagID)->get();
    $t->count = $t->count-1;
    $t-save();
    $tag->delete();
  }

  public function suggest(Request $request)
  {
    $rules = [
      'search' => 'required|min:1'
    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);

    if($validator->fails())
    {
      return Response::json(['error', 'error (suggest function tag controller)']);
    }

    $input = $request->input('search');
    $suggestions = Tag::where('name', 'LIKE', $input.'%')
    ->orderBy('count', 'DESC')->take(30)->get();

    return Response::json(['suggestions' => $suggestions]);
  }
}

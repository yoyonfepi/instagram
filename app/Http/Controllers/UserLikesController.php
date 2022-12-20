<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLikes;
use App\Models\UserProfiles;
use App\Models\UserPost;
use Illuminate\Http\JsonResponse;

class UserLikesController extends Controller
{
    public function likesImage(Request $request,$userId,$postId) {

        $query = UserLikes::where('userId', $userId)->where('userPostId', $postId)->get();

        if (count($query) > 0) { // user already likes, so will be unlikes
            $idUserLikes = $query[0]["id"];

            $userLikes = UserLikes::find($idUserLikes);
            $userLikes->delete();
        } else {
            $userLikes = new UserLikes();
            $userLikes->userId = $userId;
            $userLikes->userPostId = $postId;
            $userLikes->save();
        }

        return response()->json([
            'status' => 200,
            'message'=> 'Success'
        ]);
    }
}

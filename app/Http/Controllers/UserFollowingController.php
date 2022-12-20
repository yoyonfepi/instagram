<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFollowing;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserFollowingController extends Controller
{
    public function follow($userProfileId,$userId) {
        $query = UserFollowing::where('userProfileId', $userProfileId)->where('userId', $userId)->get();
        $alreadyFollow = count($query);

        if ($alreadyFollow <= 0) { //cannot follow anymore once follow it
            $followUser = new UserFollowing();
            $followUser->userProfileId = $userProfileId;
            $followUser->userId = $userId;
            $followUser->save();
        }

        return response()->json([
            'status' => 200,
            'message'=> 'Success'
        ]);

    }

    public function unfollow($userProfileId,$userId) {
        $find = UserFollowing::where('userProfileId', $userProfileId)->where('userId', $userId)->get();
        $followingId = $find[0]["id"];

        $query = UserFollowing::find($followingId);
        $query->delete();

        return response()->json([
            'status' => 200,
            'message'=> 'Success'
        ]);
    }

    public function followers($userId) {

        $query = DB::table('user_profiles')
            ->join('user_followings', 'user_followings.userId', '=', 'user_profiles.userId')
            ->where('user_followings.userProfileId', '=', $userId)
            ->select('userName', 'userImage','path')
            ->get();

        $followers = [];

        array_push($followers,(object) [
            'data' => $query,
            'followers' => count($query)
        ]);

        return $followers;
    }

    public function followings($userId) {

        $query = DB::table('user_profiles')
        ->join('user_followings', 'user_followings.userProfileId', '=', 'user_profiles.userId')
        ->where('user_followings.userId', '=', $userId)
        ->select('userName', 'userImage','path')
        ->get();

    $followings = [];

    array_push($followings,(object) [
        'data' => $query,
        'followings' => count($query)
    ]);

    return $followings;

    }
}

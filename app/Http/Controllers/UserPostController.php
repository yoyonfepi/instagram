<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPost;
use App\Models\user_post_details;
use App\Models\UserProfile;
use App\Models\CommentsDetail;
use App\Models\UserLikes;
use App\Models\UserComments;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Validator;

class UserPostController extends Controller
{
    public function userPost(Request $request, $userId) {

        $caption = $request->caption;

        $newPost = new UserPost();
        $newPost->userId = $userId;
        $newPost->caption = $caption;
        $newPost->datePost = now();
        $newPost->save();

        $postId = $newPost->id;
        $allowedfileExtension=['pdf','jpg','png'];
        $files = $request->image;

        foreach ($files as $data) {

            $extension = $data->getClientOriginalExtension();
            $check = in_array($extension,$allowedfileExtension);
            $path = $data->store('public/userImage');
            $name = $data->getClientOriginalName();

            if ($check) {
                $newPostDetails = new user_post_details();
                $newPostDetails->postId = $postId;
                $newPostDetails->image = $name;
                $newPostDetails->path = $path;
                $newPostDetails->save();
            }
        }

        return response()->json([
            'status' => 200,
            'message'=> 'Success'
        ]);

    }

    public function viewPost($userId,$postId) {

        /* view the user image post */
        $queryPost = DB::table('user_profiles')
        ->join('user_posts', 'user_posts.userId', '=', 'user_profiles.userId')
        ->join('user_post_details','postId','=', 'user_posts.id')
        ->where('user_profiles.userId', '=', $userId)
        ->orWhere('user_posts.id','=',$postId)
        ->select('user_post_details.image','user_post_details.path')
        ->get();

        /* count and view who is like this post */
        $queryLikes = DB::table('user_profiles')
        ->join('user_likes', 'user_likes.userId', '=', 'user_profiles.userId')
        ->join('user_posts','user_posts.id','=', 'user_likes.userPostId')
        ->where('user_profiles.userId', '=', $userId)
        ->orWhere('user_posts.id','=',$postId)
        ->select('userName', 'userImage','path')
        ->get();

        $viewPost = [];

        /* count and view who is comments this post */
        $queryComments = DB::table('user_profiles')
        ->join('user_comments', 'user_comments.userId', '=', 'user_profiles.userId')
        ->join('comments_details','commentsId','=', 'user_comments.id')
        ->join('user_posts','user_posts.id','=', 'user_comments.userPostId')
        ->where('user_profiles.userId', '=', $userId)
        ->orWhere('user_posts.id','=',$postId)
        ->select('userName', 'userImage','path','comments_details.comments')
        ->get();

        $queryCaption = DB::table('user_posts')
        ->where('userId', '=', $userId)
        ->orWhere('id','=',$postId)
        ->get();

        $imageInfo = [];
        array_push($imageInfo,(object) [
            'caption' => $queryCaption[0]->caption,
            'date' => $queryCaption[0]->datePost
        ]);

        array_push($viewPost,(object) [
            'userPost' => $queryPost,
            'imageInfo' => $imageInfo,
            'userLikes' => $queryLikes,
            'totalLikes' => count($queryLikes). ' likes',
            'comments' => $queryComments,
            'totalComments' => count($queryComments). ' comments',
        ]);

        return $viewPost;

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserComments;
use App\Models\CommentsDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserCommentsController extends Controller
{
    public function userComments(Request $request,$userId,$postId) {

        if ($request->comments !== null) {

            $query = UserComments::where('userId', $userId)->where('userPostId', $postId)->get();

            if (count($query) > 0) { // user already comments, and add new comments more
                $idUserComments = $query[0]["id"];
                $commentsDetail = new CommentsDetail();
                $commentsDetail->commentsId = $idUserComments;
                $commentsDetail->comments = $request->comments;
                $commentsDetail->commentsDate = now();
                $commentsDetail->save();

            } else {

                $newComments = new UserComments();
                $newComments->userId = $userId;
                $newComments->userPostId = $postId;
                $newComments->save();

                $commentsId = $newComments->id;

                $commentsDetail = new CommentsDetail();
                $commentsDetail->commentsId = $commentsId;
                $commentsDetail->comments = $request->comments;
                $commentsDetail->commentsDate = now();
                $commentsDetail->save();

            }
        }

        return response()->json([
            'status' => 200,
            'message'=> 'Success'
        ]);

    }

    public function updateComments(Request $request, $userId,$commentsDetailId) {

        if ($request->comments !== null) {
            $comments = CommentsDetail::find($commentsDetailId);
            $comments->comments = $request->comments;
            $comments->save();
        }

        return response()->json([
            'status' => 200,
            'message'=> 'Success'
        ]);
    }

    public function deleteComments($userId,$commentsDetailId) {

        $query = DB::table('user_comments')
        ->join('comments_details','commentsId','=','user_comments.id')
        ->where('user_comments.userId', '=', $userId)
        ->orWhere('comments_details.id','=', $commentsDetailId)
        ->get();

        $id = $query[0]->id;
        if(count($query) > 0) { // user only can delete his own comment
            $commentsDetails = CommentsDetail::find($id);
            $commentsDetails->delete();
        } else {
            return "Error";
        }

        return response()->json([
            'status' => 200,
            'message'=> 'Success'
        ]);
    }

}

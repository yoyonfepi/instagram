<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function userRegister(Request $request) {

        if ($request->userName === null || $request->userPhone === null) {
            return response()->json([
                'status' => 204,
                'message'=> 'Failed, User Name or Phone Number are required!'
            ]);
        };

        $phoneNumber = UserProfile::where('userPhone',$request->userPhone)->get();
        $numberExist = count($phoneNumber);

        if ($numberExist > 0) {
            return response()->json([
                'status' => 204,
                'message'=> 'Failed, phone number already registered!'
            ]);
        };

        $allowedfileExtension=['pdf','jpg','png'];

        if ($request->userImage !== null) {
            $files = $request->userImage;
            $extension = $files->getClientOriginalExtension();
            $check = in_array($extension,$allowedfileExtension);
            $path = $files->store('public/userProfileImage');
            $name = $files->getClientOriginalName();
        } else {
            $name = "";
            $path = "";
        }

        $newUser = new UserProfile();
        $newUser->userName = $request->userName;
        $newUser->userFirstName = $request->userFirstName;
        $newUser->userLastName = $request->userLastName;
        $newUser->userPhone = $request->userPhone;
        $newUser->userImage = $name;
        $newUser->path = $path;
        $newUser->userDateBirth = $request->userDateBirth;
        $newUser->save();

        return response()->json([
            'status' => 200,
            'message'=> 'Register success'
        ]);

    }

    public function searchUser(Request $request) {

        $input = $request->search;
        $search = UserProfile::where('userFirstName', 'LIKE', '%' . $input . '%')
                        ->orWhere('userLastName', 'LIKE', '%' . $input . '%')
                        ->orWhere('userName', 'LIKE', '%' . $input . '%')
                        ->get();
        return $search;
    }
}

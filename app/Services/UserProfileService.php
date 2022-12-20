<?php

namespace App\Services\UserProfileService;

use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\DB;

class UserRegisterService implements UserProfileController
{

/**
     * @return object|null
     */
    public function userRegisterService(Request $request): object|null
    {
        echo var_dump("masuk service");

    }
}


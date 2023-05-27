<?php

namespace App\Services;

use App\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class Service
{

    public function checkPermissions()
    {
        $user = JWTAuth::user();
        $role = Role::where('id', $user->role_id)->first();
        if ($role->name !== 'Administrator') {
            throw new HttpException(403, 'Forbidden');
        }
    }
}

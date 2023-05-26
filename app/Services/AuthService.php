<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    private $repo;

    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     *  @param  array $data
     *  @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(array $data)
    {
        try {
            $user = null;
            if (!str_contains($data["email"], "@")) {
                $user = $this->repo->getItem('nickname', $data['email']);
                if ($user) {
                    $data["email"] = $user->email;
                }
            }
            $credentials =  $data;
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "statusCode" => 400,
                    "error" => "Invalid username or password"
                ], 400);
            }
            return response()->json([
                "statusCode" => 200,
                "data" => [
                    'token' => compact('token')['token'],
                    'user' => $this->repo->getItem('email', $data['email'])
                ]
            ], 200);
        } catch (JWTException $jwt) {
            return response()->json([
                "statusCode" => 400,
                "error" => $jwt
            ], 400);
        }
    }
}

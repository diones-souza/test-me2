<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Units\Events\MessageEvent;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthService
{
    private $repo;

    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Autenticar usuário
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(array $data)
    {
        try {
            if (!str_contains($data["email"], "@")) {
                $person = $this->repo->getUser('nickname', $data['email']);
                if ($person) {
                    $data["email"] = $person->email;
                }
            }
            $credentials =  $data;
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "statusCode" => 400,
                    "action" => "Authenticate",
                    "error" => "Invalid username or password"
                ]);
            }
            return response()->json([
                "statusCode" => 200,
                "action" => "Authenticate",
                "data" => [
                    'token' => compact('token')['token'],
                    'user' => $this->repo->getUser('email', $data['email'])
                ]
            ]);
        } catch (JWTException $jwt) {
            return response()->json([
                "statusCode" => 400,
                "action" => "Authenticate",
                "error" => $jwt
            ]);
        }
    }
}

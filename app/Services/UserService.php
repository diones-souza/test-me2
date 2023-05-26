<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private $repo;

    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param  array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems(array $data)
    {
        try {
            return response()->json([
                "statusCode" => 200,
                "data" => $this->repo->getItems($data)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "statusCode" => 400,
                "error" => $th
            ]);
        }
    }
}

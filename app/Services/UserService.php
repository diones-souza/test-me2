<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "statusCode" => 400,
                "error" => $th
            ], 400);
        }
    }

    /**
     * @param  array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data["cpf"])) {
                $data["cpf"] = preg_replace('/[^0-9]/', '', $data["cpf"]);
            }
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }
            $user = $this->repo->create($data);
            DB::commit();
            return response()->json([
                "statusCode" => 201,
                "data" => $user
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => isset($th->errorInfo) && count($th->errorInfo) ? $th->errorInfo[count($th->errorInfo) - 1] : (string) $th
            ], 400);
        }
    }

    /**
     * Atualizar o registro especificado no banco de dados.
     *
     * @param  Array  $data
     * @return \Illuminate\Http\Response
     */
    public function update(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->repo->findOne($data['id']);
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            } else {
                $data['password'] = $user->password;
            }
            $user = $this->repo->update($user, $data);
            DB::commit();
            return response()->json([
                "statusCode" => 200,
                "data" => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => isset($th->errorInfo) && count($th->errorInfo) ? $th->errorInfo[count($th->errorInfo) - 1] : (string) $th
            ], 400);
        }
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $users = $this->repo->delete($id);
            DB::commit();
            return response()->json([
                "statusCode" => 200,
                "data" => $users
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => isset($th->errorInfo) && count($th->errorInfo) ? $th->errorInfo[count($th->errorInfo) - 1] : (string) $th
            ], 400);
        }
    }
}

<?php

namespace App\Services;

use App\Jobs\CreatePoint;
use App\Models\Role;
use App\Services\Service;
use App\Repositories\PointRepository;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PointService extends Service
{
    private $repo;

    public function __construct(PointRepository $repository)
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
        } catch (HttpException $e) {
            return response()->json([
                "statusCode" => $e->getStatusCode(),
                "error" => $e->getMessage()
            ], $e->getStatusCode());
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
        try {
            CreatePoint::dispatch($data)->onQueue('point');
            return response()->json([
                "statusCode" => 201,
                "message" => 'Point added queue'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "statusCode" => 400,
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Array  $data
     * @return \Illuminate\Http\Response
     */
    public function update(array $data)
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::user();
            $role = Role::where('id', $user->role_id)->first();
            if ($role->name !== 'Administrator') {
                throw new HttpException(403, 'Forbidden');
            }
            $scale = $this->repo->findOne($data['id']);
            $scale = $this->repo->update($scale, $data);
            DB::commit();
            return response()->json([
                "statusCode" => 200,
                "data" => $scale
            ], 200);
        } catch (HttpException $e) {
            return response()->json([
                "statusCode" => $e->getStatusCode(),
                "error" => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => $th->getMessage()
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
            $user = JWTAuth::user();
            $role = Role::where('id', $user->role_id)->first();
            if ($role->name !== 'Administrator') {
                throw new HttpException(403, 'Forbidden');
            }
            $scale = $this->repo->delete($id);
            DB::commit();
            return response()->json([
                "statusCode" => 200,
                "data" => $scale
            ], 200);
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                "statusCode" => $e->getStatusCode(),
                "error" => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => $th->getMessage()
            ], 400);
        }
    }
}

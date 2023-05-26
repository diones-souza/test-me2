<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Repository;

class UserRepository extends Repository
{
    protected $modelClass = User::class;

    /**
     * @param  Array  $filter
     * @return \Illuminate\Http\Response
     */
    public function getItems(array $filter)
    {
        $query = $this->newQuery()
            ->selectRaw('users.*, r.name AS role_name, s.name AS scale_name')
            ->leftJoin('scales AS s', 'users.scale_id', '=', 's.id')
            ->leftJoin('roles AS r', 'users.role_id', '=', 'r.id');
        if (isset($filter['search'])) {
            $search = $filter['search'];
            // shortcut to search only by id
            if ($search[0] === '/' && ctype_digit(substr($search, 1))) {
                return $query->where('users.id', intval(substr($search, 1)))->first();
            } else {
                $query->whereRaw("users.id || users.name || email || cpf || register || s.name ILIKE " . "'%{$search}%'");
            }
        }
        $query->orderBy('users.id');
        if (isset($filter['page'])) {
            return $query->paginate($this->paginate);
        }
        return $query->get();
    }

    /**
     * @param  string  $key
     * @param  mixed $value
     * @return object|null
     */
    public function getItem(string $key, $value)
    {
        return $this->newQuery()
            ->with('scale')
            ->with('role')
            ->where($key, $value)
            ->first();
    }

    /**
     * @param  Int  $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function delete(int $id)
    {
        $user = $this->getItem('id', $id);
        return $this->destroy($user);
    }
}

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
            ->with('role')
            ->with('scale');
        if (isset($filter['active'])) {
            $query->where('active', $filter['active']);
        } else {
            $query->where('active', 1);
        }
        if (isset($filter['search'])) {
            $search = $filter['search'];
            // shortcut to search only by id
            if ($search[0] === '/' && ctype_digit(substr($search, 1))) {
                $query->where('id', intval(substr($search, 1)));
            } else {
                $query->whereRaw("id || name || email ILIKE " . "'%{$search}%'");
            }
        }
        $query->orderBy('id');
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
    public function getUser(string $key, $value)
    {
        return $this->newQuery()
            ->with('role')
            ->with('scale')
            ->where($key, $value)
            ->first();
    }

    /**
     * @param int $id
     * @param string $key
     * @return object|null
     */
    public function getItem(int $id, string $key)
    {
        return $this->newQuery()
            ->with('role')
            ->with('scale')
            ->where($key, $id)
            ->first();
    }

    /**
     * @param  Array  $data
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(array $ids)
    {
        $ids = implode(',', $ids);
        return $this->newQuery()
            ->whereRaw("id in ($ids)")
            ->destroy();
    }
}

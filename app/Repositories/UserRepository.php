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
        $type = $filter['type'];
        $query = $this->newQuery()
            ->with('address')
            ->with('role')
            ->with('phone');
        if (isset($filter['active'])) {
            $query->where('active', $filter['active']);
        } else {
            $query->where('active', 1);
        }
        if (is_array($type)) {
            $type = implode(',', $type);
            $query->whereRaw("type in ($type)");
        } else {
            $query->where("type", $type);
        }
        if (isset($filter['search'])) {
            $search = $filter['search'];
            // atalho para buscar somente pelo id
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
            ->with('address')
            ->with('phone')
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

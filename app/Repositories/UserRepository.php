<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function all()
    {
        return $this->user->all();
    }
    public function create(array $data)
    {
        return $this->user->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->user->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->user->destroy($id);
    }

    public function find($id)
    {
        return $this->user->find($id);
    }
}
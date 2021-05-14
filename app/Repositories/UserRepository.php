<?php


namespace App\Repositories;


use App\Models\User;

class UserRepository extends BaseRepository
{
    public function getModel()
    {
        return User::class;
    }

    public function search($word)
    {
        return $this->model
            ->where('username', 'like', '%'.$word.'%')
            ->orWhere('email', 'like', '%'.$word.'%');
    }
}

<?php


namespace App\Repositories;

use App\Models\Admin;

class AdminRepository extends BaseRepository
{
    public function getModel()
    {
        return Admin::class;
    }

    public function search($word)
    {
        return $this->model
                ->where('name', 'like', '%'.$word.'%')
                ->orWhere('username', 'like', '%'.$word.'%')
                ->orWhere('email', 'like', '%'.$word.'%');
    }
}

<?php

namespace App\Transformers\User;

use App\Models\User;
use Flugg\Responder\Transformers\Transformer;

class UserTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'        => $user->id,
            'avatar'    => $user->avatar,
            'firstname' => $user->firstname,
            'lastname'  => $user->lastname,
            'username'  => $user->username,
            'email'     => $user->email,
            'address'   => $user->address,
            'phone'     => $user->phone,
        ];
    }
}

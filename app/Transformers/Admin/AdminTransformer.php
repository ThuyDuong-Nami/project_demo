<?php

namespace App\Transformers\Admin;

use App\Models\Admin;
use Flugg\Responder\Transformers\Transformer;

class AdminTransformer extends Transformer
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
     * @param  \App\Models\Admin $admin
     * @return array
     */
    public function transform(Admin $admin)
    {
        return [
            'avatar' => $admin->avatar,
            'name' => $admin->name,
            'username' => $admin->username,
            'email' => $admin->email,
        ];
    }
}

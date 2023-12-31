<?php

namespace App\Http\Requests\V1\Auth\UserAuth;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Models\User;

class UserLoginRequest extends LoginRequest
{
    protected function guardProviderModelClass(): string
    {
        return User::class;
    }
}

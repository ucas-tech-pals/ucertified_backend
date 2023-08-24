<?php

namespace App\Http\Requests\V1\Auth\InstitutionAuth;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Models\Institution;


class InstitutionLoginRequest extends LoginRequest
{
    protected function guardProviderModelClass(): string
    {
        return Institution::class;
    }
}

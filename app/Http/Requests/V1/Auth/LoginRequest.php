<?php

namespace App\Http\Requests\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

abstract class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    abstract public function rules(): array;
    // {
    //     return [
    //         'email' => ['required', 'string', 'email'],
    //         'password' => ['required', 'string'],
    //     ];
    // }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return Authenticatable the authenticated user
     * @throws ValidationException
     */
    public function authenticate(): Authenticatable
    {
        $this->ensureIsNotRateLimited();
        try{
            $user = $this->attempt($this->only('email', 'password'));
        } catch (ValidationException $e) {
            RateLimiter::hit($this->throttleKey());
            throw $e;
        }
        RateLimiter::clear($this->throttleKey());
        return $user;
    }

    /**
     * @return string the class name the model that is used as the guard provider
     */
    abstract protected function guardProviderModelClass(): string;

    /**
     * @return array<string> the columns that are used as the guard provider credentials
     */
    protected function guardProviderModelCredentialColumns(): array
    {
        return [
            'email',
        ];
    }
    /**
     * @return string the column that is used as the guard provider password
     */
    protected function guardProviderModelPasswordColumn(): string
    {
        return 'password';
    }


    /**
     * Attempt to log the user into the application.
     *
     * @param $credentials
     * @return Authenticatable
     * @throws ValidationException
     */
    public function attempt(array $credentials) : Authenticatable
    {
        $user = $this->guardProviderModelClass()::where(
            function ($query) use ($credentials) {
                foreach ($this->guardProviderModelCredentialColumns() as $column) {
                    $query->orWhere($column, $credentials[$column]);
            }
        })->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        if (!Hash::check($credentials[$this->guardProviderModelPasswordColumn()], $user[$this->guardProviderModelPasswordColumn()])) {
            throw ValidationException::withMessages([
                'password' => trans('auth.password')
            ]);
        }
        return $user;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class LoginRequest extends FormRequest
{
    protected const MAX_ATTEMPTS = 5;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'token_name' => ['required'],
            'expired_at' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticateOrFail()
    {
        $this->ensureIsNotRateLimited();

        if (\Auth::attempt($this->only('email', 'password')) === false) {
            \RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        \RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (\RateLimiter::tooManyAttempts($this->throttleKey(), static::MAX_ATTEMPTS) === false) {
            return;
        }

        event(new Lockout($this));

        $seconds = \RateLimiter::availableIn($this->throttleKey());

        throw new TooManyRequestsHttpException($seconds);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        /** @var string */
        $email = $this->input('email');

        return \Str::lower("{$email}|{$this->ip()}");
    }
}

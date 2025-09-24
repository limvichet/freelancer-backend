<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
    $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Only increment on failed login
            RateLimiter::hit($this->throttleKey(), now()->addMinutes(15));

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Success: clear failed attempts
        RateLimiter::clear($this->throttleKey());

    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $key = $this->throttleKey(); // unique per email + IP
        $maxAttempts = 5; // maximum allowed failed attempts

        // Check if user has exceeded the max attempts
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => [
                    "Too many login attempts. Please try again in {$seconds} seconds (~" . ceil($seconds / 60) . " minutes)."
                ],
            ]);
        }

        // Record a failed attempt with 15-minute decay
        // RateLimiter::hit($key, now()->addMinutes(15));
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
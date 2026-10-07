<?php
namespace App\Actions\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Log in with e-mail and password; 5 failed attempts per e-mail and IP
 * lock that pair out for a minute
 */
class LoginAction
{
  private const MAX_ATTEMPTS = 5;

  /**
   * @throws ValidationException wrong credentials or locked out (on `email`)
   */
  public function execute(array $credentials, string $ip): void
  {
    $key = Str::transliterate(Str::lower($credentials['email']) . '|' . $ip);

    if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS))
    {
      throw ValidationException::withMessages([
        'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($key)]),
      ]);
    }

    if (!Auth::attempt($credentials))
    {
      RateLimiter::hit($key);
      throw ValidationException::withMessages(['email' => __('auth.failed')]);
    }

    RateLimiter::clear($key);
  }
}

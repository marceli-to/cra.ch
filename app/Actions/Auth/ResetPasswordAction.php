<?php
namespace App\Actions\Auth;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Set a new password with a reset token and log the user in; returns the
 * broker's status (Password::PASSWORD_RESET on success)
 */
class ResetPasswordAction
{
  public function execute(array $data): string
  {
    return Password::reset($data, function (User $user, string $password) {
      $user->forceFill([
        'password' => Hash::make($password),
        'remember_token' => Str::random(60),
      ])->save();

      event(new PasswordReset($user));
      Auth::login($user);
    });
  }
}

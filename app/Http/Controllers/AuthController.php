<?php
namespace App\Http\Controllers;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\ResetPasswordAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

/**
 * Login, logout and password reset for the admin
 */
class AuthController extends Controller
{
  public function showLogin()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
    ]);

    (new LoginAction)->execute($credentials, $request->ip());
    $request->session()->regenerate();

    return redirect()->intended('/administration');
  }

  public function logout(Request $request)
  {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }

  public function showForgotPassword()
  {
    return view('auth.passwords.email');
  }

  public function sendResetLink(Request $request)
  {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
      ? back()->with('status', __($status))
      : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
  }

  public function showResetPassword(Request $request, string $token)
  {
    return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
  }

  public function resetPassword(Request $request)
  {
    $data = $request->validate([
      'token' => 'required',
      'email' => 'required|email',
      'password' => 'required|confirmed|min:8',
    ]);

    $status = (new ResetPasswordAction)->execute($data + ['password_confirmation' => $request->input('password_confirmation')]);

    if ($status !== Password::PASSWORD_RESET)
    {
      return back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }

    $request->session()->regenerate();

    return redirect('/administration')->with('status', __($status));
  }
}

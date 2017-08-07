<?php

namespace Minhbang\User\Controllers;

use Minhbang\Kit\Extensions\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Auth;
use Illuminate\Http\Request;

/**
 * Class AuthController
 *
 * @package Minhbang\User\Controllers
 */
class AuthController extends Controller
{
    use ThrottlesLogins;


    //Maximum number of login attempts for delaying further attempts.
    public $maxLoginAttempts = 5;

    //The number of seconds to delay further login attempts.
    public $lockoutTime = 120;

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogin()
    {
        return view('user::login');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, ['username' => 'required', 'password' => 'required']);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->lockoutResponse($request);
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->loginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        $this->incrementLoginAttempts($request);

        return $this->failedResponse($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect(config('user.logout_redirect', '/'));
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return config('user.username');
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function lockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        $message = trans('user::account.throttle', compact('seconds'));

        if ($request->expectsJson()) {
            return response()->json([$this->username() => $message], 423);
        }

        return back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors(['msg' => $message]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function failedResponse(Request $request)
    {
        $errors = trans('user::account.credentials_invalid');

        if ($request->expectsJson()) {
            return response()->json([$this->username() => $errors], 422);
        }

        return back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors(['msg' => $errors]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return redirect()->intended(config('user.login_redirect', '/'));
    }
}

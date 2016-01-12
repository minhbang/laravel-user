<?php
namespace Minhbang\LaravelUser\Controllers;

use Minhbang\LaravelKit\Extensions\Controller;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PasswordController
 *
 * @package Minhbang\LaravelUser\Controllers
 */
class PasswordController extends Controller
{

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function getEmail()
    {
        return view('user::password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink(
            $request->only('email'),
            function (
                /** @var \Illuminate\Mail\Message $message */
                $message
            ) {
                $message->subject(
                    '[' . setting('app.name_short') . '] ' . trans('user::account.password_reset_link')
                );
            }
        );

        switch ($response) {
            case PasswordBroker::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));

            case PasswordBroker::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
            default:
                return null;
        }
    }

    /**
     * @param null $token
     * @return $this
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('user::reset')->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postReset(Request $request)
    {
        $this->validate(
            $request,
            [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed',
            ]
        );

        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        $response = Password::reset(
            $credentials,
            function (
                /** @var \Minhbang\LaravelUser\User $user */
                $user,
                $password
            ) {
                $user->password = $password;
                $user->save();
            }
        );

        switch ($response) {
            case Password::PASSWORD_RESET:
                Session::flash(
                    'message',
                    [
                        'type'    => 'success',
                        'content' => trans('user::account.change_password_success'),
                    ]
                );
                return redirect(route('auth.login'));
            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }
}
<?php
namespace Minhbang\User\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class Role
 *
 * @package Minhbang\User\Middleware
 */
class Role
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @param string $all
     * @param string $exact
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $all = null, $exact = null)
    {
        if ($this->auth->check()) {
            if (user()->is($role, $all === 'all', $exact === 'exact')) {
                return $next($request);
            } else {
                return response(trans('common.forbidden'), 403);
            }
        } else {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(route('auth.login'));
            }
        }
    }
}

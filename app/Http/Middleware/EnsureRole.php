<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk membatasi akses berdasarkan role user.
 *
 * Cara penggunaan di routes:
 *   ->middleware('role:owner')
 *   ->middleware('role:owner,kasir')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles)) {
            // Redirect ke halaman yang sesuai dengan role mereka
            $user = $request->user();

            if ($user->isKds()) {
                return redirect()->route('kds.display');
            }

            if ($user->isKasir()) {
                return redirect()->route('kasir.dashboard');
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}

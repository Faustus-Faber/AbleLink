<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    public function handle(Request $incomingRequest, Closure $nextMiddleware): Response
    {
        $isAuthenticated = Auth::check();

        if ($isAuthenticated === true) {
            $currentUser = Auth::user();
            
            if ($currentUser !== null) {
                $isBanned = $currentUser->isBanned();

                if ($isBanned === true) {
                    $isOnBannedRoute = $incomingRequest->routeIs('banned');

                    if ($isOnBannedRoute === true) {
                        return $nextMiddleware($incomingRequest);
                    }
                    
                    $redirector = redirect();
                    return $redirector->route('banned');
                }
            }
        }

        return $nextMiddleware($incomingRequest);
    }
}

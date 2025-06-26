<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = [
            'http://localhost:5174',
            'http://127.0.0.1:5174',
        ];

        $origin = $request->headers->get('Origin');

        $isAllowedOrigin = in_array($origin, $allowedOrigins);

        $headers = [
            'Access-Control-Allow-Origin'      => $isAllowedOrigin ? $origin : $allowedOrigins[0],
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS, PATCH',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
        ];

        if ($request->getMethod() === "OPTIONS") {
            return response('', 200)->withHeaders($headers);
        }

        $response = $next($request);

        return $response->withHeaders($headers);
    }
}

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

// class Cors
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next): Response
//     {
//         return $next($request);
//     }
// }

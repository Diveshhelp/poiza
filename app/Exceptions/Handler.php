<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    // ... existing code ...

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, $exception)
    {
        // Handle API authentication errors
        if ($exception instanceof AuthenticationException && $request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Please login first.',
                'error_code' => 'UNAUTHORIZED_ACCESS'
            ], 401);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Please login first.',
                'error_code' => 'UNAUTHORIZED_ACCESS'
            ], 401);
        }

        return redirect()->guest(route('login'));
    }
}
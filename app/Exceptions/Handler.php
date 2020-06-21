<?php declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use PDOException;
use Throwable;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @throws Throwable
     *
     * @return JsonResponse
     */
    public function render($request, Throwable $exception): JsonResponse
    {
        return parent::render($request, $exception);
    }
}

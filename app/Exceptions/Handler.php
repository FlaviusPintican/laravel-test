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
        if ($request->header('Content-Type') !== 'application/json') {
            return parent::render($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->renderHttpExceptions($exception);
    }

    /**
     * @param Throwable $exception
     *
     * @return JsonResponse
     */
    private function renderHttpExceptions(Throwable $exception): JsonResponse
    {
        $response = [
            'code' => 'HTTP_BAD_REQUEST'
        ];

        $status = method_exists($exception, 'getStatusCode') ?
            $exception->getStatusCode() : Response::HTTP_BAD_REQUEST;

        if (config('app.debug')) {
            $response['exception'] = get_class($exception);
            $response['message'] = $exception->getMessage();
            $response['trace'] = $exception->getTrace();
        }

        if ($exception instanceof AuthenticationException) {
            $status = Response::HTTP_UNAUTHORIZED;
            $response['code'] = 'HTTP_UNAUTHORIZED';
        }

        if ($exception instanceof PDOException) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response['code'] = 'HTTP_INTERNAL_SERVER_ERROR';
        }

        return response()->json($response, $status);
    }
}

<?php declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Throwable $exception
     * @throws Throwable
     *
     * @return JsonResponse
     */
    public function render($request, Throwable $exception) : JsonResponse
    {
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
    private function renderHttpExceptions(Throwable $exception) : JsonResponse
    {
        $response = [
            'code' => BadRequestHttpException::class
        ];

        $status = method_exists($exception, 'getStatusCode') ?
            $exception->getStatusCode() : Response::HTTP_BAD_REQUEST;

        if (config('app.debug')) {
            $response['exception'] = get_class($exception);
            $response['message'] = $exception->getMessage();
            $response['trace'] = $exception->getTrace();
        }

        if ($exception instanceof PDOException) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($exception instanceof HttpException) {
            $response['code'] = get_class($exception);
        }

        return response()->json($response, $status);
    }
}

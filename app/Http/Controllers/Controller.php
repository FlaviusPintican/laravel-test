<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Request $request
     * @param array   $fields
     *
     * @return null|JsonResponse
     */
    protected function errors(Request $request, array $fields): ?JsonResponse
    {
        $validator = Validator::make($request->all(), $fields);

        if (!$validator->fails()) {
            return null;
        }

        return new JsonResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
    }
}

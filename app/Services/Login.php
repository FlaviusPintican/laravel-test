<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait Login
{
    /**
     * @throws AccessDeniedHttpException
     *
     * @return void
     */
    private function check(): void
    {
        if (!Auth::check()) {
            throw new AccessDeniedHttpException('Can not perform this action');
        }
    }
}

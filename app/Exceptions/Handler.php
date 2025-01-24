<?php

namespace App\Exceptions;

use Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends Exception
{
   public function render($request, Exception $exception)
    {
        return response()->json($exception->getMessage(), $exception->code);
    }
}

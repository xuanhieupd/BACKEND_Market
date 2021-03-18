<?php

namespace App\Exceptions;

use App\Response\ErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $e
     * @param Request $request
     * @return Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errorResults = array();

        foreach ($e->validator->errors()->getMessages() as $fieldName => $messages) {
            $errorResults[$fieldName] = is_array($messages) ? $messages[0] : $messages;
        }

        return response()->responseError($errorResults, 400);
    }

    /**
     * Unauthenticated
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->responseError("Phiên đăng nhập đã hết hạn. Vui lòng thực hiện đăng nhập lại.", 401);
    }

}

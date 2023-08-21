<?php

namespace App\Exceptions;

use App\Http\Resources\Api\V1\ErrorResource;
use Illuminate\Auth\AuthenticationException;

class ApiAuthenticationException extends AuthenticationException
{
    private int $statusCode;
    public function __construct($message = 'Unauthenticated.', int $statusCode, array $guards = [], $redirectTo = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $guards, $redirectTo);
    }

    public function render($request)
    {
        return ErrorResource::make($this->message, $this->statusCode);
    }
}

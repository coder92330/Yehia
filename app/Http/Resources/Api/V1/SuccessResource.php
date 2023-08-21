<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    public int $statusCode;
    public static $wrap = null;
    public string $key;

    public function __construct($resource, $statusCode = 200, $key = 'message')
    {
        parent::__construct($resource);
        $this->statusCode = $statusCode;
        $this->key        = $key;
    }

    public function toArray($request): array
    {
        return is_array($this->resource) ? $this->resource : [$this->key => $this->resource];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode($this->statusCode);
    }

    public function withWrappData()
    {
        self::$wrap = 'data';
        return $this;
    }
}

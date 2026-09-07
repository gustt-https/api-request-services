<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class DomainException extends Exception
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        string $message,
        protected int $status = 422,
        protected array $extra = [],
    ) {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json(array_merge($this->extra, [
            'success' => false,
            'message' => $this->getMessage(),
        ]), $this->status);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiException
 * @package App\Exceptions
 */
class ApiException extends Exception
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(
        string $message = '',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ) {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($message, $code);
    }

    /**
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->message
        ])->setStatusCode($this->code);
    }
}

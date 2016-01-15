<?php

namespace App\Exception;

class NotFoundException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
    public $statusCode = 404;

    public function __construct($message = null, \Exception $previous = null, array $headers = [], $code = 0)
    {
        parent::__construct($this->statusCode, $message, $previous, $headers, $this->statusCode);
    }
} 
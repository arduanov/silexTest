<?php

namespace App\Exception;


class BadRequestException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
    public $statusCode = 400;
    public $message = 'Bad Request. ';

    public function __construct($message = null, \Exception $previous = null, array $headers = [], $code = 0)
    {
        $message =  $this->message.$message;
        parent::__construct($this->statusCode, $message, $previous, $headers, $this->statusCode);
    }
}
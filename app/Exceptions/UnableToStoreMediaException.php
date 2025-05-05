<?php

namespace App\Exceptions;

class UnableToStoreMediaException extends \Exception
{
    public function __construct(string $message = "Unable to store media", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class StageNotException
 * @package App\Exceptions
 */
class StageNotException extends Exception
{
    /**
     * StageNotException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

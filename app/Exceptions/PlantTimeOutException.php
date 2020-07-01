<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class PlantTimeOutException
 * @package App\Exceptions
 */
class PlantTimeOutException extends Exception
{
    /**
     * PlantTimeOutException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

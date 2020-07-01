<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class PlantStateException
 * @package App\Exceptions
 */
class FoodRequestException extends Exception
{
    /**
     * PlantStateException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

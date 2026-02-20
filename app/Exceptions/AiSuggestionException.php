<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Exceptions;

use Exception;

class AiSuggestionException extends Exception
{
    public function __construct(string $message = 'AI suggestion failed', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

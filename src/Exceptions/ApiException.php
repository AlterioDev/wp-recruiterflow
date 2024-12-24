<?php

namespace Alterio\WPRecruiterflow\Exceptions;

/**
 * Custom exception for Recruiterflow API related errors
 */
class ApiException extends \Exception
{
    /**
     * @var array|null Response data from the API if available
     */
    protected ?array $responseData;

    /**
     * @param string $message Error message
     * @param array|null $responseData API response data
     * @param int $code Error code
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct(
        string $message,
        ?array $responseData = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->responseData = $responseData;
    }

    /**
     * Get the API response data if available
     *
     * @return array|null
     */
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }
}

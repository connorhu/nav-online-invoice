<?php

namespace NAV\OnlineInvoice\Exceptions;

use NAV\OnlineInvoice\Http\Response;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;

/**
 * The /tokenExchange call did not return a TokenExchangeResponse.
 *
 * getResponse() is the response NAV sent instead, usually a GeneralErrorResponse
 * or GeneralExceptionResponse (both implement ErrorResponse).
 */
class TokenExchangeException extends \RuntimeException
{
    public function __construct(private readonly Response $response)
    {
        if ($response instanceof ErrorResponse) {
            $message = sprintf(
                'Token exchange failed: %s %s',
                $response->getErrorCode() ?? 'unknown error',
                $response->getMessage() !== null ? '('.$response->getMessage().')' : '',
            );
        } else {
            $message = sprintf('Token exchange failed: unexpected response of type "%s"', get_debug_type($response));
        }

        parent::__construct(rtrim($message));
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}

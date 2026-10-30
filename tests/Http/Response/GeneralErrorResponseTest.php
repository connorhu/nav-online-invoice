<?php

namespace NAV\OnlineInvoice\Tests\Http\Response;

use NAV\OnlineInvoice\Http\Response\GeneralErrorResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GeneralErrorResponse::class)]
class GeneralErrorResponseTest extends TestCase
{
    public function testTechnicalValidationMessagesAreEmptyByDefault(): void
    {
        $this->assertSame([], (new GeneralErrorResponse())->getTechnicalValidationMessages());
    }

    public function testTechnicalValidationMessagesAreReturnedInOrder(): void
    {
        $response = new GeneralErrorResponse();
        $response->addTechnicalValidationMessage(['message' => 'first']);
        $response->addTechnicalValidationMessage(['message' => 'second']);

        $this->assertSame([['message' => 'first'], ['message' => 'second']], $response->getTechnicalValidationMessages());
    }
}

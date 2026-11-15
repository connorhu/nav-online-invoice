<?php

namespace NAV\OnlineInvoice\Tests\Http\Request;

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDigestRequest;
use PHPUnit\Framework\TestCase;

class QueryInvoiceDigestRequestTest extends TestCase
{
    public function testIssueDatesAcceptImmutable(): void
    {
        $request = new QueryInvoiceDigestRequest();
        $request->setIssueDateFrom(new \DateTimeImmutable('2024-01-01'));
        $request->setIssueDateTo(new \DateTimeImmutable('2024-01-31'));

        $this->assertInstanceOf(\DateTime::class, $request->getIssueDateFrom());
        $this->assertSame('2024-01-01', $request->getIssueDateFrom()->format('Y-m-d'));
        $this->assertInstanceOf(\DateTime::class, $request->getIssueDateTo());
        $this->assertSame('2024-01-31', $request->getIssueDateTo()->format('Y-m-d'));
    }

    public function testIssueDatesKeepMutableInstance(): void
    {
        $request = new QueryInvoiceDigestRequest();
        $from = new \DateTime('2024-01-01');
        $request->setIssueDateFrom($from);
        $request->setIssueDateTo(null);

        $this->assertSame($from, $request->getIssueDateFrom());
        $this->assertNull($request->getIssueDateTo());
    }
}

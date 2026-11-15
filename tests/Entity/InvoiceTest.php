<?php

namespace NAV\OnlineInvoice\Tests\Entity;

use NAV\OnlineInvoice\Model\Invoice;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    public function testSupplierTaxNumber()
    {
        $item = new Invoice();
        $item->setSupplierTaxNumber('11111111111');
        $this->assertEquals($item->getSupplierTaxNumber(), '11111111111');

        $item->setSupplierTaxNumber('111111111');
        $this->assertEquals($item->getSupplierTaxNumber(), '111111111');

        $item->setSupplierTaxNumber('11111111');
        $this->assertEquals($item->getSupplierTaxNumber(), '11111111');
    }

    public function testSupplierGroupMemberTaxNumber()
    {
        $item = new Invoice();
        $item->setSupplierGroupMemberTaxNumber('11111111111');
        $this->assertEquals($item->getSupplierGroupMemberTaxNumber(), '11111111111');

        $item->setSupplierGroupMemberTaxNumber('111111111');
        $this->assertEquals($item->getSupplierGroupMemberTaxNumber(), '111111111');

        $item->setSupplierGroupMemberTaxNumber('11111111');
        $this->assertEquals($item->getSupplierGroupMemberTaxNumber(), '11111111');

        $item->setSupplierGroupMemberTaxNumber(null);
        $this->assertEquals($item->getSupplierGroupMemberTaxNumber(), null);
    }

    public static function dateFields(): iterable
    {
        yield ['InvoiceIssueDate'];
        yield ['InvoiceDeliveryDate'];
        yield ['InvoiceAccountingDeliveryDate'];
    }

    #[DataProvider('dateFields')]
    public function testDateSetterAcceptsImmutable(string $field): void
    {
        $invoice = new Invoice();
        $invoice->{'set'.$field}(new \DateTimeImmutable('2024-01-15 10:20:30', new \DateTimeZone('Europe/Budapest')));

        $value = $invoice->{'get'.$field}();
        $this->assertInstanceOf(\DateTime::class, $value);
        $this->assertSame('2024-01-15 10:20:30 Europe/Budapest', $value->format('Y-m-d H:i:s e'));
    }

    #[DataProvider('dateFields')]
    public function testDateSetterKeepsMutableInstance(string $field): void
    {
        $invoice = new Invoice();
        $date = new \DateTime('2024-01-15');
        $invoice->{'set'.$field}($date);
        $this->assertSame($date, $invoice->{'get'.$field}());

        $invoice->{'set'.$field}(null);
        $this->assertNull($invoice->{'get'.$field}());
    }
}

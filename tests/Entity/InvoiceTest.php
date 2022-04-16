<?php

namespace NAV\Tests\OnlineInvoice\Entity;

use NAV\OnlineInvoice\Entity\Invoice;
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
}


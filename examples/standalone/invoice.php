<?php

use NAV\OnlineInvoice\Model\Address;
use NAV\OnlineInvoice\Model\Enums\CustomerVatStatusEnum;
use NAV\OnlineInvoice\Model\Enums\InvoiceAppearanceEnum;
use NAV\OnlineInvoice\Model\Enums\PaymentMethodEnum;
use NAV\OnlineInvoice\Model\Enums\UnitOfMeasureEnum;
use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Model\InvoiceItem;
use NAV\OnlineInvoice\Model\VatRateSummary;

/**
 * A minimal domestic invoice with one line at 27% VAT that passes the NAV invoiceData XSD.
 */
function createExampleInvoice(): Invoice
{
    $invoice = new Invoice();
    $invoice->setInvoiceNumber('EXAMPLE/2024/0001');
    $invoice->setInvoiceIssueDate(new \DateTime('2024-01-15'));
    $invoice->setInvoiceCategory('NORMAL');
    $invoice->setInvoiceDeliveryDate(new \DateTime('2024-01-15'));
    $invoice->setCurrencyCode('HUF');
    $invoice->setExchangeRate(1);
    $invoice->setPaymentMethod(PaymentMethodEnum::Transfer);
    $invoice->setPaymentDate(new \DateTime('2024-01-30'));
    $invoice->setInvoiceAppearance(InvoiceAppearanceEnum::Electronic);

    // tax numbers without dashes: 8 digit taxpayer id + 1 digit VAT code + 2 digit county code
    $invoice->setSupplierName('Supplier Name');
    $invoice->setSupplierTaxNumber('12345678242');
    $invoice->setSupplierBankAccountNumber('12345678-12345678-12345678');
    $invoice->setSupplierAddress((new Address())
        ->setCountryCode('HU')
        ->setPostalCode('1063')
        ->setCity('Budapest')
        ->setAdditionalAddressDetail('Podmaniczky út 1.'));

    $invoice->setCustomerVatStatus(CustomerVatStatusEnum::Domestic);
    $invoice->setCustomerName('Customer Name');
    $invoice->setCustomerTaxNumber('87654321241');
    $invoice->setCustomerAddress((new Address())
        ->setCountryCode('HU')
        ->setPostalCode('1063')
        ->setCity('Budapest')
        ->setAdditionalAddressDetail('Podmaniczky út 2.'));

    $item = new InvoiceItem();
    $item->setItemNumber(1);
    $item->setLineExpressionIndicator(true);
    $item->setLineDescription('Consulting');
    $item->setQuantity(2);
    $item->setUnitOfMeasure(UnitOfMeasureEnum::Hour);
    $item->setUnitPrice(10000);
    $item->setNetAmount(20000);
    $item->setNetAmountHUF(20000);
    $item->setVatRatePercentage(0.27);
    $item->setVatAmount(5400);
    $item->setVatAmountHUF(5400);
    $item->setGrossAmountNormal(25400);
    $item->setGrossAmountNormalHUF(25400);
    $invoice->addItem($item);

    $summary = new VatRateSummary();
    $summary->setVatRatePercentage(0.27);
    $summary->setNetAmount(20000);
    $summary->setNetAmountHUF(20000);
    $summary->setVatAmount(5400);
    $summary->setVatAmountHUF(5400);
    $summary->setGrossAmount(25400);
    $summary->setGrossAmountHUF(25400);
    $invoice->addVatRateSummary($summary);

    $invoice->setInvoiceNetAmount(20000);
    $invoice->setInvoiceNetAmountHUF(20000);
    $invoice->setInvoiceVatAmount(5400);
    $invoice->setInvoiceVatAmountHUF(5400);
    $invoice->setInvoiceGrossAmount(25400);
    $invoice->setInvoiceGrossAmountHUF(25400);

    return $invoice;
}

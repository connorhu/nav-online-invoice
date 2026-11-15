<?php

namespace NAV\OnlineInvoice\Tests\Http\Request;

use NAV\OnlineInvoice\Http\Request\InvoiceNumberQueryRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceCheckRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceDataRequest;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * The validation mapping in lib/Resources/validator/validation.xml mirrors InvoiceNumberQueryType.
 */
class QueryInvoiceCheckRequestTest extends TestCase
{
    public static function requestClasses(): iterable
    {
        yield 'QueryInvoiceCheckRequest' => [QueryInvoiceCheckRequest::class];
        yield 'QueryInvoiceDataRequest' => [QueryInvoiceDataRequest::class];
    }

    private static function validate(object $request): ConstraintViolationListInterface
    {
        $validator = Validation::createValidatorBuilder()
            ->addXmlMapping(__DIR__.'/../../../lib/Resources/validator/validation.xml')
            ->getValidator();

        return $validator->validate($request, null, ['v3.0']);
    }

    /**
     * @return list<string>
     */
    private static function violatedPaths(ConstraintViolationListInterface $violations): array
    {
        $paths = [];
        foreach ($violations as $violation) {
            $paths[] = $violation->getPropertyPath();
        }

        return array_values(array_unique($paths));
    }

    public function testDefaults(): void
    {
        $request = new QueryInvoiceCheckRequest();

        $this->assertSame('/queryInvoiceCheck', $request->getEndpointPath());
        $this->assertSame(RequestServiceKindEnum::InvoiceService, $request->getServiceKind());
        $this->assertSame(InvoiceNumberQueryRequest::INVOICE_DIRECTION_OUTBOUND, $request->getInvoiceDirection());
        $this->assertNull($request->getBatchIndex());
        $this->assertNull($request->getSupplierTaxNumber());
    }

    public function testSupplierTaxNumberIsCutToTheTaxpayerId(): void
    {
        $request = new QueryInvoiceCheckRequest();
        $request->setSupplierTaxNumber('12345678-2-41');
        $this->assertSame('12345678', $request->getSupplierTaxNumber());

        $request->setSupplierTaxNumber(null);
        $this->assertNull($request->getSupplierTaxNumber());
    }

    #[DataProvider('requestClasses')]
    public function testValidRequest(string $class): void
    {
        $request = new $class();
        $request->setInvoiceNumber('INV/2024/0001');
        $request->setInvoiceDirection(InvoiceNumberQueryRequest::INVOICE_DIRECTION_INBOUND);
        $request->setBatchIndex(1);
        $request->setSupplierTaxNumber('12345678');

        $this->assertSame([], self::violatedPaths(self::validate($request)));
    }

    #[DataProvider('requestClasses')]
    public function testMissingInvoiceNumber(string $class): void
    {
        $this->assertSame(['invoiceNumber'], self::violatedPaths(self::validate(new $class())));
    }

    #[DataProvider('requestClasses')]
    public function testInvalidValues(string $class): void
    {
        $request = new $class();
        $request->setInvoiceNumber(str_repeat('A', 51));
        $request->setInvoiceDirection('SIDEWAYS');
        $request->setBatchIndex(0);
        $request->setSupplierTaxNumber('1234');

        $this->assertSame(
            ['invoiceNumber', 'invoiceDirection', 'batchIndex', 'supplierTaxNumber'],
            self::violatedPaths(self::validate($request)),
        );
    }

    public function testBlankInvoiceNumber(): void
    {
        $request = new QueryInvoiceCheckRequest();
        $request->setInvoiceNumber('   ');

        $this->assertSame(['invoiceNumber'], self::violatedPaths(self::validate($request)));
    }
}

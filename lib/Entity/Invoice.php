<?php

namespace NAV\OnlineInvoice\Entity;

use NAV\OnlineInvoice\Entity\Interfaces\InvoiceInterface;
use NAV\OnlineInvoice\Serialize\XMLSerialize;
use NAV\OnlineInvoice\Validator\Constraints as NavAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ node_name invoice
 */
class Invoice implements InvoiceInterface
{
    public function __construct()
    {
        $this->supplierAddress = new Address();
    }
    
    /**
     * Belföldi adószám, amely alatt a számlán szereplő termékértékesítés vagy szolgáltatás nyújtás történt. Lehet csoportazonosító szám is.
     * The domestic tax number under which the sale of the product or provision of service was carried out. It can also be a group ID.
     *
     * @ requirements required
     * @ node_name supplierTaxNumber
     * @ simple_type TaxNumberType
     * @ xml_type xs:complexType
     * @ pattern -
     * @ enum -
     * @ default -
     *
     * <code>
     *    <invoiceExchange>
     *        <invoiceHead>
     *            <supplierInfo>
     *                <supplierTaxNumber>
     *                    <taxpayerId>11111111</taxpayerId>
     *                    <vatCode>2</vatCode>
     *                    <countyCode>42</countyCode>
     *                </supplierTaxNumber>
     * </code>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @NavAssert\TaxNumber(groups={"v2.0"})
     */
    protected $supplierTaxNumber;
    
    /**
     * setter for supplierTaxNumber
     *
     * @param string 
     * @return self
     */
    public function setSupplierTaxNumber(string $value): InvoiceInterface
    {
        $this->supplierTaxNumber = str_replace('-', '', $value);
        return $this;
    }
    
    /**
     * getter for supplierTaxNumber
     * 
     * @return string
     */
    public function getSupplierTaxNumber(): string
    {
        return $this->supplierTaxNumber;
    }

    /*
     * Csoport tag adószáma, ha a termékértékesítés vagy szolgáltatásnyújtás csoportazonosító szám alatt történt
     *
     * requirements: not required
     * node name: groupMemberTaxNumber
     * xml type: TaxNumberType
     * simple type: xs:complexType
     * pattern: 

    <invoiceExchange>
        <invoiceHead>
            <supplierInfo>
                <groupMemberTaxNumber>
                    <taxpayerId>11111111</taxpayerId>
                    <vatCode>2</vatCode>
                    <countyCode>42</countyCode>
                </groupMemberTaxNumber>
     *
     * @NavAssert\TaxNumber(groups={"v2.0"})
     */
    protected $supplierGroupMemberTaxNumber;
    
    /**
     * setter for supplierGroupMemberTaxNumber
     *
     * @param mixed 
     * @return self
     */
    public function setSupplierGroupMemberTaxNumber(?string $value): InvoiceInterface
    {
        $this->supplierGroupMemberTaxNumber = $value;
        return $this;
    }
    
    /**
     * getter for supplierGroupMemberTaxNumber
     * 
     * @return mixed return value for 
     */
    public function getSupplierGroupMemberTaxNumber(): ?string
    {
        return $this->supplierGroupMemberTaxNumber;
    }

    /*
     * Közösségi adószám
     *
     * requirements: not required
     * node name: communityVatNumber
     * xml type: CommunityVatNumberType
     * simple type: CommunityVatNumberType
     * pattern: [A-Z]{2}[0-9A-Z]{2,13}

    <invoiceExchange>
        <invoiceHead>
            <supplierInfo>
                <communityVatNumber>ZZ1111111111111</communityVatNumber>
     */
    protected $supplierCommunityVatNumber;
    
    /**
     * setter for supplierCommunityVatNumber
     *
     * @param mixed 
     * @return self
     */
    public function setSupplierCommunityVatNumber(?string $value): InvoiceInterface
    {
        $this->supplierCommunityVatNumber = $value;
        return $this;
    }
    
    /**
     * getter for supplierCommunityVatNumber
     * 
     * @return mixed return value for 
     */
    public function getSupplierCommunityVatNumber(): ?string
    {
        return $this->supplierCommunityVatNumber;
    }

    /*
     * Az eladó (szállító) neve
     *
     * @ requirements required
     * @ node_name supplierName
     * @ simple_type SimpleText512NotBlankType
     * @ xml_type xs:string
     * @ pattern .*[^\s].*
     * @ enum -
     * @ default -
     *
     * <code>
     *    <invoiceExchange>
     *        <invoiceHead>
     *            <supplierInfo>
     *                <supplierName>Szállító Kft</supplierName>
     * </code>
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max = 512)
     */
    protected $supplierName;
    
    /**
     * setter for supplierName
     *
     * @param mixed 
     * @return self
     */
    public function setSupplierName(string $value): InvoiceInterface
    {
        $this->supplierName = $value;
        return $this;
    }
    
    /**
     * getter for supplierName
     * 
     * @return mixed return value for 
     */
    public function getSupplierName(): string
    {
        return $this->supplierName;
    }
    
    /*
     * Az eladó (szállító) címe
     *
     * @ requirements required
     * @ node_name supplierAddress
     * @ simple_type AddressType
     * @ xml_type xs:complexType
     * @ pattern -
     * @ enum -
     * @ default -
     *
     * <code>
     *     <invoiceExchange>
     *         <invoiceHead>
     *             <supplierInfo>
     *                 <supplierAddress>
     *                     <detailedAddress>
     *                         <countryCode>HU</countryCode>
     *                         <postalCode>1111</postalCode>
     *                         <city>Budapest</city>
     *                         <streetName>Példa</streetName>
     *                         <publicPlaceCategory>utca</publicPlaceCategory>
     *                         <number>777</number>
     *                         <floor>1.</floor>
     *                         <door>3.</door>
     *                     </detailedAddress>
     *                 </supplierAddress>
     * </code>
     *
     * @Assert\NotBlank()
     * @Assert\Valid(group="v2.0")
     */
    protected $supplierAddress;
    
    /**
     * setter for supplierAddress
     *
     * @param mixed 
     * @return self
     */
    public function setSupplierAddress(Address $value): InvoiceInterface
    {
        $this->supplierAddress = $value;
        return $this;
    }
    
    /**
     * getter for supplierAddress
     * 
     * @return mixed return value for 
     */
    public function getSupplierAddress(): Address
    {
        return $this->supplierAddress;
    }
    
    /*
     * Az eladó (szállító) bankszámlaszáma
     *
     * requirements: not required
     * node name: supplierBankAccountNumber
     * xml type: xs:string
     * simple type: BankAccountNumberType
     * pattern: [0-9]{8}[-][0-9]{8}[-][0-9]{8}|[0-9]{8}[-][0-9]{8}|[A-Z]{2}[0-9]{2}[0-9A-Za-z]{11,30}
     *
     * 12345678-12345678 vagy,
     * 12345678-12345678-12345678 vagy,
     * Kétbetűs országkód + kétjegyű ellenőrzőszám + 11-30 számjegyű belföldi pénzforgalmi jelzőszám IBAN
     *

	<invoiceExchange>
		<invoiceHead>
			<supplierInfo>
				<supplierBankAccountNumber>88888888-66666666-12345678</supplierBankAccountNumber>
    
     */
    protected $supplierBankAccountNumber;
    
    /**
     * setter for supplierBankAccountNumber
     *
     * @param mixed 
     * @return self
     */
    public function setSupplierBankAccountNumber(?string $value): InvoiceInterface
    {
        $this->supplierBankAccountNumber = $value;
        return $this;
    }
    
    /**
     * getter for supplierBankAccountNumber
     * 
     * @return mixed return value for 
     */
    public function getSupplierBankAccountNumber(): ?string
    {
        return $this->supplierBankAccountNumber;
    }
    
    /*
     * Adószám, amely alatt a számlán szereplő termékbeszerzés vagy szolgáltatás igénybevétele történt. Lehet csoportazonosító szám is.
     *
     * requirements: required
     * node name: customerTaxNumber
     * xml type: TaxNumberType
     * simple type: TaxNumberType
     * pattern: -
    
	<invoiceExchange>
		<invoiceHead>
			<customerInfo>
				<customerTaxNumber>
					<taxpayerId>33333333</taxpayerId>
					<vatCode>2</vatCode>
					<countyCode>02</countyCode>
				</customerTaxNumber>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @NavAssert\TaxNumber(groups={"v2.0"})
     */
    protected $customerTaxNumber;
    
    /**
     * setter for customerTaxNumber
     *
     * @param mixed 
     * @return self
     */
    public function setCustomerTaxNumber($value)
    {
        $this->customerTaxNumber = str_replace('-', '', $value);
        return $this;
    }
    
    /**
     * getter for customerTaxNumber
     * 
     * @return mixed return value for 
     */
    public function getCustomerTaxNumber()
    {
        return $this->customerTaxNumber;
    }
    
    /*
     * Csoport tag adószáma, ha a termékértékesítés vagy szolgáltatásnyújtás csoportazonosító szám alatt történt.
     *
     * requirements: not required
     * node name: groupMemberTaxNumber
     * xml type: TaxNumberType
     * simple type: TaxNumberType
     * pattern: -

	<invoiceExchange>
		<invoiceHead>
			<customerInfo>
				<groupMemberTaxNumber>
					<taxpayerId>33333333</taxpayerId>
					<vatCode>2</vatCode>
					<countyCode>02</countyCode>
				</groupMemberTaxNumber>
     *
     * @NavAssert\TaxNumber(groups={"v2.0"})
     */
    protected $customerGroupMemberTaxNumber;
    
    /**
     * setter for customerGroupMemberTaxNumber
     *
     * @param mixed 
     * @return self
     */
    public function setCustomerGroupMemberTaxNumber($value)
    {
        $this->customerGroupMemberTaxNumber = $value;
        return $this;
    }
    
    /**
     * getter for customerGroupMemberTaxNumber
     * 
     * @return mixed return value for 
     */
    public function getCustomerGroupMemberTaxNumber()
    {
        return $this->customerGroupMemberTaxNumber;
    }
    
    /*
     * Közösségi adószám
     *
     * requirements: not required
     * node name: communityVatNumber
     * xml type: xs:string
     * simple type: CommunityVatNumberType
     * pattern: [A-Z]{2}[0-9A-Z]{2,13}

	<invoiceExchange>
		<invoiceHead>
			<customerInfo>
				<groupMemberTaxNumber>
					<taxpayerId>33333333</taxpayerId>
					<vatCode>2</vatCode>
					<countyCode>02</countyCode>
				</groupMemberTaxNumber>
     */
    protected $customerCommunityVatNumber;
    
    /**
     * setter for customerCommunityVatNumber
     *
     * @param mixed 
     * @return self
     */
    public function setCustomerCommunityVatNumber($value)
    {
        $this->customerCommunityVatNumber = $value;
        return $this;
    }
    
    /**
     * getter for customerCommunityVatNumber
     * 
     * @return mixed return value for 
     */
    public function getCustomerCommunityVatNumber()
    {
        return $this->customerCommunityVatNumber;
    }
    
    /*
     * A vevő neve
     *
     * requirements: required
     * node name: customerName
     * xml type: xs:string
     * simple type: SimpleText512NotBlankType
     * pattern: .*[^\s].*

	<invoiceExchange>
		<invoiceHead>
			<customerInfo>
				<customerName>Vevő Kft</customerName>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $customerName;
    
    /**
     * setter for customerName
     *
     * @param mixed 
     * @return self
     */
    public function setCustomerName($value)
    {
        $this->customerName = $value;
        return $this;
    }
    
    /**
     * getter for customerName
     * 
     * @return mixed return value for 
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }
    
    /*
     * A vevő címe
     *
     * requirements: required
     * node name: customerAddress
     * xml type: AddressType
     * simple type: AddressType
     * pattern: -

	<invoiceExchange>
		<invoiceHead>
			<customerInfo>
				<customerAddress>
					<detailedAddress>
						<countryCode>HU</countryCode>
						<postalCode>7600</postalCode>
						<city>Pécs</city>
						<streetName>Kitalált</streetName>
						<publicPlaceCategory>köz</publicPlaceCategory>
						<number>8</number>
					</detailedAddress>
				</customerAddress>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @NavAssert\Valid(groups={"v2.0"})
     */
    protected $customerAddress;
    
    /**
     * setter for customerAddress
     *
     * @param mixed 
     * @return self
     */
    public function setCustomerAddress(Address $value)
    {
        $this->customerAddress = $value;
        return $this;
    }
    
    /**
     * getter for customerAddress
     * 
     * @return mixed return value for 
     */
    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }
    
    /*
     * Vevő bankszámlaszáma
     *
     * requirements: not required
     * node name: customerBankAccountNumber
     * xml type: xs:string 
     * simple type: BankAccountNumberType
     * pattern: [0-9]{8}[-][0-9]{8}[-][0-9]{8}|[0-9]{8}[-][0-9]{8}|[A-Z]{2}[0-9]{2}[0-9A-Zaz]{11,30}

	<invoiceExchange>
		<invoiceHead>
			<customerInfo>
				<customerBankAccountNumber>88888888-66666666-12345678</customerBankAccountNumber>
     */
    protected $customerBankAccountNumber;
    
    /**
     * setter for customerBankAccountNumber
     *
     * @param mixed 
     * @return self
     */
    public function setCustomerBankAccountNumber($value)
    {
        $this->customerBankAccountNumber = $value;
        return $this;
    }
    
    /**
     * getter for customerBankAccountNumber
     * 
     * @return mixed return value for 
     */
    public function getCustomerBankAccountNumber()
    {
        return $this->customerBankAccountNumber;
    }
    
    // TODO: fiscalRepresentativeInfo nem implementált
    
    /*
     * Számla vagy módosító okirat sorszáma - ÁFA tv. 169. § b) vagy 170. § (1) bek. b) pont
     *
     * requirements: required
     * node name: invoiceNumber
     * xml type: xs:string
     * simple type: SimpleText50NotBlankType
     * pattern: .*[^\s].*

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceNumber>T20190001</invoiceNumber>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $invoiceNumber;
    
    /**
     * setter for invoiceNumber
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceNumber($value)
    {
        $this->invoiceNumber = $value;
        return $this;
    }
    
    /**
     * getter for invoiceNumber
     * 
     * @return mixed return value for 
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }
    
    const INVOCE_CATEGORY_NORMAL = 'NORMAL';
    const INVOCE_CATEGORY_SIMPLIFIED = 'SIMPLIFIED';
    const INVOCE_CATEGORY_AGGREGATE = 'AGGREGATE';
    
    /*
     * A számla típusa, módosító okirat esetén az eredeti számla típusa
     *
     * requirements: required
     * node name: invoiceCategory
     * xml type: xs:string
     * simple type: InvoiceCategoryType
     * pattern: -
     * enum: NORMAL SIMPLIFIED AGGREGATE

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceCategory>NORMAL</invoiceCategory>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $invoiceCategory;
    
    /**
     * setter for invoiceCategory
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceCategory($value)
    {
        $this->invoiceCategory = $value;
        return $this;
    }
    
    /**
     * getter for invoiceCategory
     * 
     * @return mixed return value for 
     */
    public function getInvoiceCategory()
    {
        return $this->invoiceCategory;
    }
    
    /*
     * Számla kelte - ÁFA tv. 169. § a)
     *
     * requirements: not required
     * node name: invoiceIssueDate
     * xml type: xs:date
     * simple type: DateType
     * pattern: \d{4}-\d{2}-\d{2}

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceIssueDate>2019-05-15</invoiceIssueDate>
     */
    protected $invoiceIssueDate;
    
    /**
     * setter for invoiceIssueDate
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceIssueDate(\DateTime $value = null)
    {
        $this->invoiceIssueDate = $value;
        return $this;
    }
    
    /**
     * getter for invoiceIssueDate
     * 
     * @return mixed return value for 
     */
    public function getInvoiceIssueDate()
    {
        return $this->invoiceIssueDate;
    }

    /*
     * Teljesítés dátuma (ha nem szerepel a számlán, akkor azonos a számla keltével) - ÁFA tv. 169. § g)
     *
     * requirements: not required
     * node name: invoiceDeliveryDate
     * xml type: xs:date
     * simple type: DateType
     * pattern: \d{4}-\d{2}-\d{2}

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceDeliveryDate>2019-05-10</invoiceDeliveryDate>
     */
    protected $invoiceDeliveryDate;
    
    /**
     * setter for invoiceDeliveryDate
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceDeliveryDate(\DateTime $value = null)
    {
        $this->invoiceDeliveryDate = $value;
        return $this;
    }
    
    /**
     * getter for invoiceDeliveryDate
     * 
     * @return mixed return value for 
     */
    public function getInvoiceDeliveryDate()
    {
        return $this->invoiceDeliveryDate;
    }
    
    /*
     * Amennyiben a számla egy időszakra vonatkozik, akkor az időszak első napja
     *
     * requirements: not required
     * node name: invoiceDeliveryPeriodStart
     * xml type: xs:date
     * simple type: DateType
     * pattern: \d{4}-\d{2}-\d{2}


	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceDeliveryPeriodStart>2019-05-10</invoiceDeliveryPeriodStart>
     */
    protected $invoiceDeliveryPeriodStart;
    
    /**
     * setter for invoiceDeliveryPeriodStart
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceDeliveryPeriodStart($value)
    {
        $this->invoiceDeliveryPeriodStart = $value;
        return $this;
    }
    
    /**
     * getter for invoiceDeliveryPeriodStart
     * 
     * @return mixed return value for 
     */
    public function getInvoiceDeliveryPeriodStart()
    {
        return $this->invoiceDeliveryPeriodStart;
    }
    
    /*
     * Amennyiben a számla egy időszakra vonatkozik, akkor az időszak utolsó napja.
     *
     * requirements: not required
     * node name: invoiceDeliveryPeriodEnd
     * xml type: xs:date
     * simple type: DateType
     * pattern: \d{4}-\d{2}-\d{2}

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceDeliveryPeriodEnd>2019-05-10</invoiceDeliveryPeriodEnd>
     */
    protected $invoiceDeliveryPeriodEnd;
    
    /**
     * setter for invoiceDeliveryPeriodEnd
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceDeliveryPeriodEnd($value)
    {
        $this->invoiceDeliveryPeriodEnd = $value;
        return $this;
    }
    
    /**
     * getter for invoiceDeliveryPeriodEnd
     * 
     * @return mixed return value for 
     */
    public function getInvoiceDeliveryPeriodEnd()
    {
        return $this->invoiceDeliveryPeriodEnd;
    }
    
    /*
     * Számviteli teljesítés dátuma. Időszak esetén az időszak utolsó napja.
     *
     * requirements: not required
     * node name: invoiceAccountingDeliveryDate
     * xml type: xs:date
     * simple type: DateType
     * pattern: \d{4}-\d{2}-\d{2}

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceAccountingDeliveryDate>2019-05-10</invoiceAccountingDeliveryDate>
     */
    protected $invoiceAccountingDeliveryDate;
    
    /**
     * setter for invoiceAccountingDeliveryDate
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceAccountingDeliveryDate(\DateTime $value = null)
    {
        $this->invoiceAccountingDeliveryDate = $value;
        return $this;
    }
    
    /**
     * getter for invoiceAccountingDeliveryDate
     * 
     * @return mixed return value for 
     */
    public function getInvoiceAccountingDeliveryDate()
    {
        return $this->invoiceAccountingDeliveryDate;
    }
    
    /*
     * A számla pénzneme az ISO 4217 szabvány szerint
     *
     * requirements: required
     * node name: currencyCode
     * xml type: xs:string
     * simple type: CurrencyType
     * pattern: [A-Z]{3}

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<currencyCode>HUF</currencyCode>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $currencyCode;
    
    /**
     * setter for currencyCode
     *
     * @param mixed 
     * @return self
     */
    public function setCurrencyCode($value)
    {
        $this->currencyCode = $value;
        return $this;
    }
    
    /**
     * getter for currencyCode
     * 
     * @return mixed return value for 
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }
    
    /*
     * HUF-tól különböző pénznem esetén az alkalmazott árfolyam: egy egység értéke HUF-ban.
     * Az árfolyam típus a különböző árfolyamok leírására szolgál. Legfeljebb 14 számjegyet tartalmazhat, ebből legfeljebb 6 lehet a tizedesponttól jobbra. Értéke csak pozitív lehet. 
     *
     * requirements: not required
     * node name: exchangeRate
     * xml type: xs:decimal
     * simple type: ExchangeRateType
     * pattern: -

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<exchangeRate>1521.154</exchangeRate>
     */
    protected $exchangeRate;
    
    /**
     * setter for exchangeRate
     *
     * @param mixed 
     * @return self
     */
    public function setExchangeRate($value)
    {
        $this->exchangeRate = $value;
        return $this;
    }
    
    /**
     * getter for exchangeRate
     * 
     * @return mixed return value for 
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }
    
    /*
     * Önszámlázás jelölése (önszámlázás esetén true)
     *
     * requirements: not required
     * node name: selfBillingIndicator
     * xml type: xs:boolean
     * simple type: boolean
     * pattern: -

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<selfBillingIndicator>false</selfBillingIndicator>
     */
    protected $selfBillingIndicator;
    
    /**
     * setter for selfBillingIndicator
     *
     * @param mixed 
     * @return self
     */
    public function setSelfBillingIndicator($value)
    {
        $this->selfBillingIndicator = $value;
        return $this;
    }
    
    /**
     * getter for selfBillingIndicator
     * 
     * @return mixed return value for 
     */
    public function getSelfBillingIndicator()
    {
        return $this->selfBillingIndicator;
    }
    
    const PATMENT_METHOD_TRANSFER = 'TRANSFER';
    const PATMENT_METHOD_CASH = 'CASH';
    const PATMENT_METHOD_CARD = 'CARD';
    const PATMENT_METHOD_VOUCHER = 'VOUCHER';
    const PATMENT_METHOD_OTHER = 'OTHER';
    
    /*
     * Fizetés módja
     *
     * requirements: not required
     * node name: paymentMethod
     * xml type: xs:string
     * simple type: PaymentMethodType
     * pattern: -
     * enum: TRANSFER CASH CARD VOUCHER OTHER 

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<paymentMethod>TRANSFER</paymentMethod>
     */
    protected $paymentMethod;
    
    /**
     * setter for paymentMethod
     *
     * @param mixed 
     * @return self
     */
    public function setPaymentMethod($value)
    {
        $this->paymentMethod = $value;
        return $this;
    }
    
    /**
     * getter for paymentMethod
     * 
     * @return mixed return value for 
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
    
    /*
     * Fizetési határidő
     *
     * requirements: not required
     * node name: paymentDate
     * xml type: xs:date
     * simple type: DateType
     * pattern: \d{4}-\d{2}-\d{2}

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<paymentDate>2019-05-30</paymentDate>
     */
    protected $paymentDate;
    
    /**
     * setter for paymentDate
     *
     * @param mixed 
     * @return self
     */
    public function setPaymentDate($value)
    {
        $this->paymentDate = $value;
        return $this;
    }
    
    /**
     * getter for paymentDate
     * 
     * @return mixed return value for 
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }
    
    /*
     * Pénzforgalmi elszámolás jelölése, ha az szerepel a számlán - ÁFA tv. 169. § h). 
     *
     * requirements: not required
     * node name: cashAccountingIndicator
     * xml type: xs:boolean
     * simple type: boolean
     * pattern: -

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<cashAccountingIndicator>false</cashAccountingIndicator>
     */
    protected $cashAccountingIndicator;
    
    /**
     * setter for cashAccountingIndicator
     *
     * @param mixed 
     * @return self
     */
    public function setCashAccountingIndicator($value)
    {
        $this->cashAccountingIndicator = $value;
        return $this;
    }
    
    /**
     * getter for cashAccountingIndicator
     * 
     * @return mixed return value for 
     */
    public function getCashAccountingIndicator()
    {
        return $this->cashAccountingIndicator;
    }
    
    const INVOCE_APPEARANCE_PAPER = 'PAPER';
    const INVOCE_APPEARANCE_ELECTRONIC = 'ELECTRONIC';
    const INVOCE_APPEARANCE_EDI = 'EDI';
    const INVOCE_APPEARANCE_UNKNOWN = 'UNKNOWN';
    
    /*
     * A számla vagy módosító okirat megjelenési formája.
     *
     * requirements: required
     * node name: invoiceAppearance
     * xml type: xs:string
     * simple type: InvoiceAppearanceType
     * pattern: -
     * enum: PAPER ELECTRONIC EDI UNKNOWN

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<invoiceAppearance>PAPER</invoiceAppearance>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $invoiceAppearance;
    
    /**
     * setter for invoiceAppearance
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceAppearance($value)
    {
        $this->invoiceAppearance = $value;
        return $this;
    }
    
    /**
     * getter for invoiceAppearance
     * 
     * @return mixed return value for 
     */
    public function getInvoiceAppearance()
    {
        return $this->invoiceAppearance;
    }
    
    /*
     * Elektronikus számla vagy módosító okirat állomány SHA256 lenyomata.
     *
     * requirements: not required
     * node name: electronicInvoiceHash
     * xml type: xs:string
     * simple type: SHA256Type
     * pattern: [0-9A-F]{64}

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<electronicInvoiceHash>...</electronicInvoiceHash>
     */
    protected $electronicInvoiceHash;
    
    /**
     * setter for electronicInvoiceHash
     *
     * @param mixed 
     * @return self
     */
    public function setElectronicInvoiceHash($value)
    {
        $this->electronicInvoiceHash = $value;
        return $this;
    }
    
    /**
     * getter for electronicInvoiceHash
     * 
     * @return mixed return value for 
     */
    public function getElectronicInvoiceHash()
    {
        return $this->electronicInvoiceHash;
    }
    
    /*
     * A számlára vonatkozó egyéb adat
     *
     * requirements: not required
     * node name: additionalInvoiceData
     * xml type: AdditionalDataType
     * simple type: AdditionalDataType
     * pattern: 

	<invoiceExchange>
		<invoiceHead>
			<invoiceData>
				<additionalInvoiceData>
					<dataName>X00001_MJ</dataName>
					<dataDescription>Tulajdonjog fenntartás jelzése</dataDescription>
					<dataValue>A számlán szereplő áruk az ellenérték kiegyenlítéséig az eladó tulajdonát képezik.</dataValue>
				</additionalInvoiceData>
     */
    protected $additionalInvoiceData = [];
    
    /**
     * setter for additionalInvoiceData
     *
     * @param mixed 
     * @return self
     */
    public function setAdditionalInvoiceData($value)
    {
        $this->additionalInvoiceData = $value;
        return $this;
    }
    
    /**
     * getter for additionalInvoiceData
     * 
     * @return mixed return value for 
     */
    public function getAdditionalInvoiceData()
    {
        return $this->additionalInvoiceData;
    }
    
    /*
     * 
     *
     * requirements: required
     * node name: 
     * xml type: 
     * simple type: 
     * pattern: 

	<invoiceExchange>
		<invoiceLines>
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @NavAssert\Valid(groups={"v2.0"})
     */
    protected $items = [];
    
    /**
     * Add item
     *
     * @param AppBundle\Document\item item
     */
    public function addItem(InvoiceItem $item)
    {
        $this->items[] = $item;
        return $this;
    }
    
    /**
     * Remove item
     *
     * @param AppBundle\Document\Item item
     */
    public function removeItem(InvoiceItem $item)
    {
        $this->items->removeElement($item);
        return $this;
    }
    
    /**
     * Getter for items
     * 
     * @return mixed return value for Doctrine\Common\Collections\ArrayCollection|null
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /*
     * A számla bruttó összege a számla pénznemében
     *
     * requirements: not required
     * node name: invoiceGrossAmount
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

     */
    protected $invoiceGrossAmount;
    
    /**
     * setter for invoiceGrossAmount
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceGrossAmount($value)
    {
        $this->invoiceGrossAmount = $value;
        return $this;
    }
    
    /**
     * getter for invoiceGrossAmount
     * 
     * @return mixed return value for 
     */
    public function getInvoiceGrossAmount()
    {
        return $this->invoiceGrossAmount;
    }
    
    /*
     * A számla bruttó összege forintban
     *
     * requirements: not required
     * node name: invoiceGrossAmountHUF
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

     */
    protected $invoiceGrossAmountHUF;
    
    /**
     * setter for invoiceGrossAmount
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceGrossAmountHUF($value)
    {
        $this->invoiceGrossAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for invoiceGrossAmount
     * 
     * @return mixed return value for 
     */
    public function getInvoiceGrossAmountHUF()
    {
        return $this->invoiceGrossAmountHUF;
    }
    
    /*
     * A számla nettó összege a számla pénznemében
     *
     * requirements: required
     * node name: invoiceNetAmount
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $invoiceNetAmount;
    
    /**
     * setter for invoiceNetAmount
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceNetAmount($value)
    {
        $this->invoiceNetAmount = $value;
        return $this;
    }
    
    /**
     * getter for invoiceNetAmount
     * 
     * @return mixed return value for 
     */
    public function getInvoiceNetAmount()
    {
        return $this->invoiceNetAmount;
    }
    
    
    /*
     * A számla nettó összege forintban
     *
     * requirements: required
     * node name: invoiceNetAmountHUF
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $invoiceNetAmountHUF;
    
    /**
     * setter for invoiceNetAmount
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceNetAmountHUF($value)
    {
        $this->invoiceNetAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for invoiceNetAmount
     * 
     * @return mixed return value for 
     */
    public function getInvoiceNetAmountHUF()
    {
        return $this->invoiceNetAmountHUF;
    }
    
    /*
     * A számla ÁFA összege a számla pénznemében
     *
     * requirements: required
     * node name: invoiceVatAmount
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $invoiceVatAmount;
    
    /**
     * setter for invoiceVatAmount
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceVatAmount($value)
    {
        $this->invoiceVatAmount = $value;
        return $this;
    }
    
    /**
     * getter for invoiceVatAmount
     * 
     * @return mixed return value for 
     */
    public function getInvoiceVatAmount()
    {
        return $this->invoiceVatAmount;
    }
    
    /*
     * A számla ÁFA összege forintban 
     *
     * requirements: required
     * node name: invoiceVatAmountHUF
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

     *
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $invoiceVatAmountHUF;
    
    /**
     * setter for invoiceVatAmountHUF
     *
     * @param mixed 
     * @return self
     */
    public function setInvoiceVatAmountHUF($value)
    {
        $this->invoiceVatAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for invoiceVatAmountHUF
     * 
     * @return mixed return value for 
     */
    public function getInvoiceVatAmountHUF()
    {
        return $this->invoiceVatAmountHUF;
    }
    
    /**
     * @Assert\NotBlank(groups={"v2.0"})
     */
    protected $vatRateSummaries = [];
    
    /**
     * Add vatRateSummary
     *
     * @param AppBundle\Document\vatRateSummary vatRateSummary
     */
    public function addVatRateSummary(VatRateSummary $vatRateSummary)
    {
        $this->vatRateSummaries[] = $vatRateSummary;
        return $this;
    }
    
    /**
     * Remove vatRateSummary
     *
     * @param AppBundle\Document\VatRateSummary vatRateSummary
     */
    public function removeVatRateSummary(VatRateSummary $vatRateSummary)
    {
        $this->vatRateSummaries->removeElement($vatRateSummary);
        return $this;
    }
    
    /**
     * Getter for vatRateSummarys
     * 
     * @return mixed return value for Doctrine\Common\Collections\ArrayCollection|null
     */
    public function getVatRateSummaries()
    {
        return $this->vatRateSummaries;
    }
    
    protected $originalInvoiceNumber;
    
    /**
     * setter for originalInvoiceNumber
     *
     * @param mixed 
     * @return self
     */
    public function setOriginalInvoiceNumber($value)
    {
        $this->originalInvoiceNumber = $value;
        return $this;
    }
    
    /**
     * getter for originalInvoiceNumber
     * 
     * @return mixed return value for 
     */
    public function getOriginalInvoiceNumber()
    {
        return $this->originalInvoiceNumber;
    }
    
    protected $modificationIssueDate;
    
    /**
     * setter for modificationIssueDate
     *
     * @param mixed 
     * @return self
     */
    public function setModificationIssueDate($value)
    {
        $this->modificationIssueDate = $value;
        return $this;
    }
    
    /**
     * getter for modificationIssueDate
     * 
     * @return mixed return value for 
     */
    public function getModificationIssueDate()
    {
        return $this->modificationIssueDate;
    }
    
    protected $modificationTimestamp;
    
    /**
     * setter for modificationTimestamp
     *
     * @param mixed 
     * @return self
     */
    public function setModificationTimestamp($value)
    {
        $this->modificationTimestamp = $value;
        return $this;
    }
    
    /**
     * getter for modificationTimestamp
     * 
     * @return mixed return value for 
     */
    public function getModificationTimestamp()
    {
        return $this->modificationTimestamp;
    }
    
    protected $lastModificationReference;
    
    /**
     * setter for lastModificationReference
     *
     * @param mixed 
     * @return self
     */
    public function setLastModificationReference($value)
    {
        $this->lastModificationReference = $value;
        return $this;
    }
    
    /**
     * getter for lastModificationReference
     * 
     * @return mixed return value for 
     */
    public function getLastModificationReference()
    {
        return $this->lastModificationReference;
    }
    
    protected $modifyWithoutMaster = false;
    
    /**
     * setter for modifyWithoutMaster
     *
     * @param mixed 
     * @return self
     */
    public function setModifyWithoutMaster($value)
    {
        $this->modifyWithoutMaster = $value;
        return $this;
    }
    
    /**
     * getter for modifyWithoutMaster
     * 
     * @return mixed return value for 
     */
    public function getModifyWithoutMaster()
    {
        return $this->modifyWithoutMaster;
    }
}


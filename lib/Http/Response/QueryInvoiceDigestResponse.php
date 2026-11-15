<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Response;
use NAV\OnlineInvoice\Model\InvoiceDigest;

class QueryInvoiceDigestResponse extends Response
{
    private int $currentPage;

    private int $availablePage;

    /**
     * @var array<int, InvoiceDigest>
     */
    private array $invoiceDigests = [];

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getAvailablePage(): int
    {
        return $this->availablePage;
    }

    public function setAvailablePage(int $availablePage): self
    {
        $this->availablePage = $availablePage;

        return $this;
    }

    /**
     * @return array<int, InvoiceDigest>
     */
    public function getInvoiceDigests(): array
    {
        return $this->invoiceDigests;
    }

    public function addInvoiceDigest(InvoiceDigest $invoiceDigest): self
    {
        $this->invoiceDigests[] = $invoiceDigest;

        return $this;
    }
}

<?php

namespace NAV\OnlineInvoice\Entity;

use NAV\OnlineInvoice\Model\Interfaces as Model;

trigger_deprecation('connorhu/nav-online-invoice', '0.1', 'Use of classes and interfaces in the Entity namespace is deprecated. The `Model` namespace equivalent is recommended.');

if (\false) {
    /**
     * @deprecated
     * @see Model\InvoiceInterface
     */
    interface InvoiceInterface
    {
    }
}

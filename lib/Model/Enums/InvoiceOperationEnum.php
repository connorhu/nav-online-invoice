<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum InvoiceOperationEnum: string
{
    case Create = 'CREATE';
    case Modify = 'MODIFY';
    case Storno = 'STORNO';

    /**
     * @deprecated
     */
    case Annul = 'ANNUL';
}

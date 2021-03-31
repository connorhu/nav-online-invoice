<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

interface ResponseDenormalizerInterface
{
    const API_SCHEMAS_URL_V30 = 'http://schemas.nav.gov.hu/OSA/3.0/api';
    const DATA_SCHEMAS_URL_V30 = 'http://schemas.nav.gov.hu/OSA/3.0/data';
    const COMMON_SCHEMAS_URL_V10 = 'http://schemas.nav.gov.hu/NTCA/1.0/common';
    const BASE_SCHEMAS_URL_V30 = 'http://schemas.nav.gov.hu/OSA/3.0/base';
}
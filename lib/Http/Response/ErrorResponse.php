<?php

namespace NAV\OnlineInvoice\Http\Response;

interface ErrorResponse
{
    public function getFunctionCode(): ?string;
    public function getErrorCode(): ?string;
    public function getMessage(): ?string;
}
<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

trait ResponseDenormalizerTrait
{
    protected static function getNamespaceWithUrl(string $url, array $data): ?string
    {
        foreach ($data as $key => $value) {
            if (substr($key, 0, 6) !== '@xmlns') {
                continue;
            }

            if ($value === $url) {
                return ltrim(substr($key, 6), ':');
            }
        }

        return null;
    }
}
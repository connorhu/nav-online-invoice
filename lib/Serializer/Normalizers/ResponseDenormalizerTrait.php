<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

trait ResponseDenormalizerTrait
{
    protected static function getNamespaceWithUrl(string $url, array $data): ?string
    {
        foreach ($data as $key => $value) {
            if (!str_starts_with($key, '@xmlns')) {
                continue;
            }

            if ($value === $url) {
                return ltrim(substr($key, 6), ':');
            }
        }

        return null;
    }

    protected static function getNamespaceKeyPrefix(string $url, array $data): string
    {
        $namespace = self::getNamespaceWithUrl($url, $data);

        return empty($namespace) ? '' : ($namespace.':');
    }
}
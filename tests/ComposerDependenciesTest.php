<?php

namespace NAV\OnlineInvoice\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Every third-party package referenced from lib/ must be a runtime dependency (require),
 * otherwise a project installing only this library cannot use it.
 */
class ComposerDependenciesTest extends TestCase
{
    private const PACKAGES = [
        'Symfony\\Component\\Serializer\\' => 'symfony/serializer',
        'Symfony\\Component\\Validator\\' => 'symfony/validator',
        'Symfony\\Component\\HttpClient\\' => 'symfony/http-client',
        'Symfony\\Contracts\\HttpClient\\' => 'symfony/http-client', // symfony/http-client-contracts comes with it
        'Psr\\Log\\' => 'psr/log',
        'Doctrine\\Common\\Collections\\' => 'doctrine/collections',
    ];

    public function testPackagesUsedInLibAreRequired(): void
    {
        $composer = json_decode(file_get_contents(__DIR__.'/../composer.json'), true, flags: JSON_THROW_ON_ERROR);

        $used = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__.'/../lib'));
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            preg_match_all('/^use\s+\\\\?((?:Symfony|Psr|Doctrine)\\\\[\w\\\\]+)/m', file_get_contents($file), $matches);
            foreach ($matches[1] as $class) {
                $package = null;
                foreach (self::PACKAGES as $prefix => $candidate) {
                    if (str_starts_with($class, $prefix)) {
                        $package = $candidate;
                        break;
                    }
                }

                $this->assertNotNull($package, sprintf('%s uses %s, add its package to %s::PACKAGES', $file, $class, self::class));
                $used[$package] = true;
            }
        }

        $this->assertNotEmpty($used);
        foreach (array_keys($used) as $package) {
            $this->assertArrayHasKey($package, $composer['require'], sprintf('"%s" is used in lib/ but not in "require"', $package));
        }
    }
}

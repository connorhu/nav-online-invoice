<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Exceptions\InvalidArgumentException;
use NAV\OnlineInvoice\Exceptions\UnexpectedTypeException;
use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Http\Request\Software;


class CompactDataProvider implements SoftwareProviderInterface, UserProviderInterface, RequestIdProviderInterface, ApiEndpointUrlProviderInterface, CryptoToolsProviderInterface
{
    /**
     * @var array{
     *     "software": array{
     *         id: string,
     *         name: string,
     *         operation: string,
     *         mainVersion: string,
     *         devName: string,
     *         devContact: string,
     *         devCountryCode: string,
     *         devTaxNumber: string
     *     },
     *     "user": array{
     *         login: string,
     *         password: string,
     *         taxNumber: string,
     *         signKey: string
     *     }
     * }
     */
    private array $infoJson;
    private Software $software;
    private User $user;
    
    public function __construct(string $infoJsonFilePath)
    {
        $this->infoJson = json_decode(file_get_contents($infoJsonFilePath), true);
        
        $softwareKeys = [
            'id',
            'name',
            'operation',
            'mainVersion',
            'devName',
            'devContact',
            'devCountryCode',
            'devTaxNumber',
        ];
        $this->software = new Software();
        foreach ($softwareKeys as $key) {
            $this->software->{'set'.ucfirst($key)}($this->infoJson['software'][$key]);
        }
        
        $userKeys = [
            'login',
            'password',
            'taxNumber',
            'signKey',
        ];
        $this->user = new User();
        foreach ($userKeys as $key) {
            $this->user->{'set'.ucfirst($key)}($this->infoJson['user'][$key]);
        }
    }
    
    public function getSoftware(): Software
    {
        return $this->software;
    }
    
    public function getRequestId(): string
    {
        return $this->infoJson['requestPrefix'] . str_replace('.', '', microtime(true));
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function getEndpointUrl(Request $request): string
    {
        return sprintf('https://%s/%s%s%s',
            $this->infoJson['test'] ? 'api-test.onlineszamla.nav.gov.hu' : 'api.onlineszamla.nav.gov.hu',
            $request->getServiceKind()->value,
            '/v3',
            $request->getEndpointPath(),
        );
    }
    
    public function getUserPasswordHash(User $user): string
    {
        return strtoupper(hash('sha512', $user->getPassword()));
    }
    
    public function getUserPasswordHashAlgo(User $user): string
    {
        return 'SHA-512';
    }
    
    public function signRequest(Request $request, iterable $content = null): string
    {
        if (!$request instanceof Request\UserAwareRequest) {
            throw new UnexpectedTypeException($request, Request\UserAwareRequest::class);
        }

        if (!$request instanceof Request\HeaderAwareRequest) {
            throw new UnexpectedTypeException($request, Request\HeaderAwareRequest::class);
        }

        $buffer = $request->getRequestId();
        $buffer .= $request->getHeader()->getTimestamp()->format('YmdHis');
        $buffer .= $request->getUser()->getSignKey();
        
        if ($content !== null) {
            foreach ($content as $item) {
                $buffer .= strtoupper(hash('sha3-512', $item));
            }
        }
        
        $requestVersion = $request->getRequestVersion();
        if ($requestVersion === RequestVersionEnum::v10 || $requestVersion === RequestVersionEnum::v11) {
            return strtoupper(hash('sha512', $buffer));
        }
        if ($requestVersion === RequestVersionEnum::v20 || $requestVersion === RequestVersionEnum::v30) {
            return strtoupper(hash('sha3-512', $buffer));
        }

        throw new InvalidArgumentException(sprintf('Invalid request version: "%s"', $requestVersion->name));
    }
    
    public function getRequestSignatureHashAlgo(Request $request): string
    {
        return 'SHA3-512';
    }
    
    public function encodeInvoiceData(string $invoiceData, Request $request = null): string
    {
        return base64_encode($invoiceData);
    }
    
    public function decodeExchangeToken(string $encodedToken): string
    {
        return openssl_decrypt($encodedToken, 'AES-128-ECB', $this->infoJson['user']['xmlExchangeKey']);
    }
}

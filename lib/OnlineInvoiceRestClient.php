<?php

namespace Nav;

use Psr\Log\LoggerInterface;
use NAV\OnlineInvoice\Exceptions\ConstraintViolationException;
use NAV\OnlineInvoice\Http\Response;
use NAV\OnlineInvoice\Providers\ApiEndpointUrlProviderInterface;
use NAV\OnlineInvoice\Providers\RequestIdProviderInterface;
use NAV\OnlineInvoice\Providers\ResponseClassProvider;
use NAV\OnlineInvoice\Providers\SoftwareProviderInterface;
use NAV\OnlineInvoice\Providers\UserProviderInterface;

use NAV\OnlineInvoice\Http\ExhangeTokenAwareRequest;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\TokenExchange;
use NAV\OnlineInvoice\NavRestClient;
use NAV\OnlineInvoice\Entity\InvoiceInterface;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceStatusRequest;
use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OnlineInvoiceRestClient
{
    private $validator;
    private $serializer;
    
    const VERSION_10 = 'v1.0';
    const VERSION_11 = 'v1.1';
    const VERSION_20 = 'v2.0';
    
    private $version = 'v2.0';
    
    public function __construct(SoftwareProviderInterface $softwareProvider, ValidatorInterface $validator, SerializerInterface $serializer, RequestIdProviderInterface $requestIdProvider, UserProviderInterface $userProvider, ApiEndpointUrlProviderInterface $urlProvider, LoggerInterface $logger = null)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->softwareProvider = $softwareProvider;
        $this->requestIdProvider = $requestIdProvider;
        $this->userProvider = $userProvider;
        $this->urlProvider = $urlProvider;
        $this->logger = $logger;
    }
    
    public function setVersion(string $version)
    {
        $this->version = $version;
    }
    
    public function sendRequest($request)
    {
        $request->setSoftware($this->softwareProvider->getSoftware());

        // basic request type :: basic header type
        $header = new Header();
        $header->setTimestamp(new \DateTime());
        
        if ($this->version === self::VERSION_10) {
            $header->setRequestVersion(Header::REQUEST_VERSION_V10);
        }
        elseif ($this->version === self::VERSION_11) {
            $header->setRequestVersion(Header::REQUEST_VERSION_V11);
        }
        elseif ($this->version === self::VERSION_20) {
            $header->setRequestVersion(Header::REQUEST_VERSION_V20);
        }
        
        $request->setHeader($header);
        
        $errors = $this->validator->validate($request, null, [$this->version]);
        
        if (count($errors) > 0) {
            throw new ConstraintViolationException('the request is invalid', $errors);
        }

        if ($request instanceof ExhangeTokenAwareRequest) {
            $token = $this->getExhangeToken();
            $request->setExchangeToken($token);
        }

        $xmlStringBody = $this->serializer->serialize($request, 'xml', [
            'xml_root_node_name' => $request->getRootNodeName(),
        ]);

        try {
            $endpointUrl = $this->urlProvider->getEndpointUrl($request);
            
            $this->logger->info('Request will send: '. $endpointUrl);
            $this->logger->info('Request body: '. $xmlStringBody);

            $client = HttpClient::create();
            $response = $client->request('POST', $endpointUrl, [
                'headers' => [
                    'content-type' => 'application/xml',
                    'accept' => 'application/xml',
                ],
                'body' => $xmlStringBody,
            ]);
            $statusCode = $response->getStatusCode();
            $content = $response->getContent();
            
            $responseClass = ResponseClassProvider::getResponseClass($content, 'xml');
            $response = $this->serializer->deserialize($content, $responseClass, 'xml');

            $this->logger->info('Request did send: '. $endpointUrl);
            $this->logger->info('Response body: '. $content);
        }
        catch (ServerException $e) {
            $responseXmlString = $e->getResponse()->getContent(false);

            $responseClass = ResponseClassProvider::getResponseClass($responseXmlString, 'xml');
            $response = $this->serializer->deserialize($responseXmlString, $responseClass, 'xml');

            $this->logger->info('ServerException: '. $responseXmlString);
        }
        catch (ClientException $e) {
            $responseXmlString = $e->getResponse()->getContent(false);

            $responseClass = ResponseClassProvider::getResponseClass($responseXmlString, 'xml');
            $response = $this->serializer->deserialize($responseXmlString, $responseClass, 'xml');

            $this->logger->info('ClientException: '. $responseXmlString);
        }
        catch (\Exception $e) {
            $this->logger->info('Unknown exception: '. $e->getMessage());
            
            dump($e);
            exit;
            
            throw $e;
        }
        
        return $response;
    }
    
    public function getExhangeToken(): string
    {
        $request = new TokenExchangeRequest();
        $request->setRequestId($this->requestIdProvider->getRequestId());
        $request->setUser($this->userProvider->getUser());
        
        $response = $this->sendRequest($request);
        
        return $response->getExchangeToken();
    }
}

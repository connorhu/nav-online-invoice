<?php

namespace NAV\OnlineInvoice;

use Psr\Log\LoggerInterface;
use NAV\OnlineInvoice\Exceptions\ConstraintViolationException;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Providers\ApiEndpointUrlProviderInterface;
use NAV\OnlineInvoice\Providers\RequestIdProviderInterface;
use NAV\OnlineInvoice\Providers\ResponseClassProvider;
use NAV\OnlineInvoice\Providers\SoftwareProviderInterface;
use NAV\OnlineInvoice\Providers\UserProviderInterface;

use NAV\OnlineInvoice\Http\ExchangeTokenAwareRequest;
use NAV\OnlineInvoice\Http\Request\Header;use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Http\Request\SoftwareAwareRequest;
use NAV\OnlineInvoice\Http\Request\HeaderAwareRequest;
use NAV\OnlineInvoice\Http\Request\UserAwareRequest;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

class OnlineInvoiceRestClient
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    
    const VERSION_10 = 'v1.0';
    const VERSION_11 = 'v1.1';
    const VERSION_20 = 'v2.0';
    const VERSION_30 = 'v3.0';

    private string $version = 'v3.0';

    /**
     * @var SoftwareProviderInterface
     */
    private SoftwareProviderInterface $softwareProvider;

    /**
     * @var RequestIdProviderInterface
     */
    private RequestIdProviderInterface $requestIdProvider;

    /**
     * @var UserProviderInterface
     */
    private UserProviderInterface $userProvider;

    /**
     * @var ApiEndpointUrlProviderInterface
     */
    private ApiEndpointUrlProviderInterface $urlProvider;

    /**
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger;

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
        if ($request instanceof SoftwareAwareRequest) {
            $request->setSoftware($this->softwareProvider->getSoftware());
        }

        if ($request instanceof HeaderAwareRequest) {
            // basic request type :: basic header type
            $header = new Header();
            $header->setTimestamp(new \DateTime());
            $request->setHeader($header);
        }
        
        if ($request instanceof UserAwareRequest) {
            $request->setUser($this->userProvider->getUser());
        }

        if ($this->version === self::VERSION_10) {
            $request->setRequestVersion(Request::REQUEST_VERSION_V10);
        }
        elseif ($this->version === self::VERSION_11) {
            $request->setRequestVersion(Request::REQUEST_VERSION_V11);
        }
        elseif ($this->version === self::VERSION_20) {
            $request->setRequestVersion(Request::REQUEST_VERSION_V20);
        }
        elseif ($this->version === self::VERSION_30) {
            $request->setRequestVersion(Request::REQUEST_VERSION_V30);
        }
        
        $request->setRequestId($this->requestIdProvider->getRequestId());
        
        $errors = $this->validator->validate($request, null, [$this->version]);
        
        if (count($errors) > 0) {
            throw new ConstraintViolationException('The request is invalid', $errors);
        }

        if ($request instanceof ExchangeTokenAwareRequest) {
            $token = $this->getExhangeToken();
            $request->setExchangeToken($token);
        }
        
        $options = [
            XmlEncoder::FORMAT_OUTPUT => true,
        ];

        $xmlStringBody = $this->serializer->serialize($request, 'request', $options);

        try {
            $endpointUrl = $this->urlProvider->getEndpointUrl($request);
            
            if ($this->logger) {
                $this->logger->info('Request will send: '. $endpointUrl);
                $this->logger->info('Request body: '. $xmlStringBody);
            }

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

            if ($this->logger) {
                $this->logger->info('Request did send: '. $endpointUrl);
                $this->logger->info('Response body: '. $content);
            }

            $responseClass = ResponseClassProvider::getResponseClass($content, 'xml');
            $response = $this->serializer->deserialize($content, $responseClass, 'request');
        }
        catch (ServerException $e) {
            $responseXmlString = $e->getResponse()->getContent(false);
            
            dump($responseXmlString);
            
            if ($this->logger) {
                $this->logger->info('ServerException: '. $responseXmlString);
            }

            $responseClass = ResponseClassProvider::getResponseClass($responseXmlString, 'xml');
            $response = $this->serializer->deserialize($responseXmlString, $responseClass, 'xml');
        }
        catch (ClientException $e) {
            $responseXmlString = $e->getResponse()->getContent(false);
            
            dump($responseXmlString);

            if ($this->logger) {
                $this->logger->info('ClientException: '. $responseXmlString);
            }

            $responseClass = ResponseClassProvider::getResponseClass($responseXmlString, 'xml');
            $response = $this->serializer->deserialize($responseXmlString, $responseClass, 'xml');
        }
        catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->info('Unknown exception: '. $e->getMessage());
            }
            
            dump($e);

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

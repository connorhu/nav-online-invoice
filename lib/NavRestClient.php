<?php

namespace Nav;

use DateTime;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

use NAV\OnlineInvoice\Http\Response;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Serialize\XMLSerialize;

use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\User;

// debug
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;

class NavRestClient
{
    public function __construct($test, $login, $password, $signKey, $softwareInfo, $baseTaxnumber, $requestPrefix = 'KANDM', $logger = null)
    {
        $this->test = $test;
        $this->login = $login;
        $this->password = $password;
        $this->signKey = $signKey;
        $this->softwareInfo = Software::initWithArray($softwareInfo);
        $this->baseTaxnumber = $baseTaxnumber;
        $this->requestPrefix = $requestPrefix;
        
        $this->logger = $logger;
    }

    public function sendRequest(Request $request)
    {
        // basic request type :: software type
        $request->setSoftwareInformations($this->softwareInfo);

        // basic request type :: basic header type
        $header = new Header($this->requestPrefix);
        $header->updateRequestId();
        $header->setTimestamp(new DateTime());
        $request->setHeader($header);

        // basic request type :: basic user type
        $user = new User();
        $user->setLogin($this->login);
        $user->setPassword($this->password);
        $user->setTaxnumber($this->baseTaxnumber);
        
        $request->setUser($user);
        
        $request->signRequest($this->signKey);

        $client = new HttpClient();
        
        $xmlSerialize = new XMLSerialize();
        
        $body = $xmlSerialize->serialize($request);
        
        try {
            $endpointUrl = $request->getEndpointUrl($this->test);
            
            $this->logger->info('Request will send: '. $endpointUrl);
            $this->logger->info('Request body: '. $body);
            
            $response = $client->post($endpointUrl, [
                'body' => $body,
                'headers' => $request->getHeaders(),
                'connect_timeout' => 10,
            ]);

            $responseXml = (string) $response->getBody();

            $this->logger->info('Request did send: '. $endpointUrl);
            $this->logger->info('Response body: '. $responseXml);
        }
        catch (ServerException $e) {
            $responseXml = (string) $e->getResponse()->getBody();

            $this->logger->info('ServerException: '. $responseXml);
        }
        catch (ClientException $e) {
            $responseXml = (string) $e->getResponse()->getBody();

            $this->logger->info('ClientException: '. $responseXml);
        }
        catch (\Exception $e) {
            $this->logger->info('Guzzler exception: '. $e->getMessage());
            
            throw $e;
        }
        
        return Response::createResponse($responseXml);
    }
}

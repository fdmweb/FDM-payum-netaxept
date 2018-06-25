<?php

/*
 * This file is part of the Netaxept Payum Gateway package.
 *
 * (c) Andrew Plank
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\TestCase\Action;

use FDM\Netaxept\Api;
use FDM\Netaxept\Traits\FileLoaderTrait;
use FDM\Payum\Netaxept\NetaxeptGatewayFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payum\Core\GatewayInterface;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    use FileLoaderTrait;

    /**
     * @var GatewayInterface
     */
    protected $gateway;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $gatewayFactory = new NetaxeptGatewayFactory();
        $this->gateway = $gatewayFactory->create([
            'merchantId' => 'unused',
            'token' => 'unused',
            'sandbox' => true,
            'letNetaxeptGenerateTransactionId' => true,
            'transactionIdTemplate' => '',
        ]);
    }

    /**
     * Prepares an instance of the API object, mocked with the appropriate Guzzle client so it returns the specified
     * XML fixtures file as a response.
     *
     * @param string $fixturesFile
     *
     * @return Api
     */
    protected function getInstanceForRequestFixture($fixturesFile)
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'text/xml'], $this->fileGetContents($fixturesFile)),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return new Api('placeholdermerchant', 'placeholdertoken', null, null, $client, true);
    }

    /**
     * Just creates an instance of the API.
     *
     * @return Api
     */
    protected function getInstanceWithNoQueuedRequests()
    {
        return new Api('placeholdermerchant', 'placeholdertoken', null, null, null, true);
    }
}

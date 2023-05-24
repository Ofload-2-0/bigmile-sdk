<?php

namespace Ofload\BigmileSdk\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Ofload\BigmileSdk\Client\OauthClient;
use Ofload\BigmileSdk\Support\CredentialsValueObject;
use Ofload\BigmileSdk\Exceptions\OauthClientAccessTokenException;
use PHPUnit\Framework\TestCase;

class OauthTest extends TestCase
{
    public function testItShouldHandleBadRequestResponse(): void
    {
        $this->expectExceptionCode(OauthClient::HTTP_BAD_REQUEST);
        $this->expectException(OauthClientAccessTokenException::class);

        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(OauthClient::HTTP_BAD_REQUEST)
            ])
        );

        $client = new OauthClient(
            new Client(['handler' => $handleStack])
        );
        $client->getAccessToken($this->getCredentials());
    }

    public function testItShouldReturnAccessToken(): void
    {
        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(
                    OauthClient::HTTP_OK,
                    [],
                    $this->successResponse()
                )
            ])
        );

        $client = new OauthClient(
            new Client(['handler' => $handleStack])
        );

        $accessTokenDTO = $client->getAccessToken($this->getCredentials());

        $this->assertNotEmpty($accessTokenDTO->getAccessToken());
    }

    private function successResponse(): string
    {
        return '{"token_type":"Bearer","expires_in":3599,"ext_expires_in":3599,"access_token":"e"}';
    }

    private function getCredentials(): CredentialsValueObject
    {
        return new CredentialsValueObject(
            'test',
            'test',
            'test'
        );
    }
}
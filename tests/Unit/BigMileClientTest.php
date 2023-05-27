<?php

namespace Ofload\BigMileSdk\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Ofload\BigMileSdk\Client\BigMileClient;
use Ofload\BigMileSdk\DTOs\AccessTokenDTO;
use Ofload\BigMileSdk\DTOs\AddressDTO;
use Ofload\BigMileSdk\DTOs\CalculatedShipmentDTO;
use Ofload\BigMileSdk\DTOs\CalculateEmissionRequestDTO;
use Ofload\BigMileSdk\DTOs\CredentialsDTO;
use Ofload\BigMileSdk\Exceptions\OauthClientAccessTokenException;
use Ofload\BigMileSdk\Tests\Fixtures\EmissionCalculationFixture;
use PHPUnit\Framework\TestCase;

class BigMileClientTest extends TestCase
{
    public function testItShouldHandleBadRequestResponse(): void
    {
        $this->expectExceptionCode(BigMileClient::HTTP_BAD_REQUEST);
        $this->expectException(OauthClientAccessTokenException::class);

        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(BigMileClient::HTTP_BAD_REQUEST)
            ])
        );

        $client = new BigMileClient(
            new Client(['handler' => $handleStack])
        );
        $client->getAccessToken($this->getCredentials());
    }

    public function testItShouldReturnAccessToken(): void
    {
        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_OK,
                    [],
                    $this->successResponse()
                )
            ])
        );

        $client = new BigMileClient(
            new Client(['handler' => $handleStack])
        );

        $accessTokenDTO = $client->getAccessToken($this->getCredentials());

        $this->assertNotEmpty($accessTokenDTO->getAccessToken());
    }

    public function testItShouldCaptureBadRequestExceptionDuringEmissionCalculation(): void
    {
        $this->expectExceptionCode(BigMileClient::HTTP_BAD_REQUEST);

        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_BAD_REQUEST,
                    [],
                    $this->errorResponse(BigMileClient::HTTP_BAD_REQUEST)
                )
            ])
        );

        $client = new BigMileClient(
            new Client(['handler' => $handleStack])
        );
        $accessTokenDTO = $client->getAccessToken($this->getCredentials());
        $client->calculateEmission($this->getEmptyEmission(), $accessTokenDTO);
    }

    public function testItShouldCaptureUnprocessableEntityExceptionDuringEmissionCalculation(): void
    {
        $this->expectExceptionCode(BigMileClient::HTTP_UNPROCESSABLE_ENTITY);

        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_UNPROCESSABLE_ENTITY,
                    [],
                    $this->errorResponse(BigMileClient::HTTP_UNPROCESSABLE_ENTITY)
                )
            ])
        );

        $client = new BigMileClient(
            new Client(['handler' => $handleStack])
        );
        $accessTokenDTO = $client->getAccessToken($this->getCredentials());
        $client->calculateEmission($this->getEmptyEmission(), $accessTokenDTO);
    }

    public function testItShouldCaptureUnauthorizedExceptionDuringEmissionCalculation(): void
    {
        $this->expectExceptionCode(BigMileClient::HTTP_UNAUTHORIZED);

        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_UNAUTHORIZED,
                    [],
                    $this->errorResponse(BigMileClient::HTTP_UNAUTHORIZED)
                )
            ])
        );

        $client = new BigMileClient(
            new Client(['handler' => $handleStack])
        );
        $accessTokenDTO = $client->getAccessToken($this->getCredentials());
        $client->calculateEmission($this->getEmptyEmission(), $accessTokenDTO);
    }

    public function testItShouldCaptureTooManyRequestsExceptionDuringEmissionCalculation(): void
    {
        $this->expectExceptionCode(BigMileClient::HTTP_TOO_MANY_REQUESTS);

        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_TOO_MANY_REQUESTS,
                    [],
                    $this->errorResponse(BigMileClient::HTTP_TOO_MANY_REQUESTS)
                )
            ])
        );

        $client = new BigMileClient(
            new Client(['handler' => $handleStack])
        );
        $accessTokenDTO = $client->getAccessToken($this->getCredentials());
        $client->calculateEmission($this->getEmptyEmission(), $accessTokenDTO);
    }

    public function testItShouldCalculateEmission(): void
    {
        $accessTokenHandle = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_OK,
                    [],
                    $this->successResponse()
                )
            ])
        );
        $calculateEmissionHandle = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_OK,
                    [],
                    json_encode(EmissionCalculationFixture::get())
                )
            ])
        );

        $clientHandler = new Client(['handler' => $accessTokenHandle]);
        $client = new BigMileClient($clientHandler);
        $accessTokenDTO = $client->getAccessToken($this->getCredentials());
        $handlers = $clientHandler->getConfig()['handler'];
        $handlers->setHandler($calculateEmissionHandle);
        $response = $client->calculateEmission($this->getEmptyEmission(), $accessTokenDTO);

        $leg = EmissionCalculationFixture::get()['legs'][0];
        /** @var CalculatedShipmentDTO $expectedLeg */
        $expectedLeg = $response->getLegs()[0];
        $calculatedParams = $expectedLeg->getCalculationParameters();

        $this->assertNotEmpty($response->getLegs());
        $this->assertEquals($leg['legId'], $expectedLeg->getLegId());
        $this->assertEquals($leg['CO2eTTW'], $expectedLeg->getCO2eTTW());
        $this->assertEquals($leg['CO2eWTW'], $expectedLeg->getCO2eWTW());
        $this->assertNotEmpty($calculatedParams);
        $this->assertEquals($leg['calculationParameters']['lang'], $calculatedParams->getLang());
        $this->assertEquals($leg['calculationParameters']['name'], $calculatedParams->getName());
        $this->assertEquals($leg['calculationParameters']['type'], $calculatedParams->getType());
        $this->assertEquals($leg['calculationParameters']['frameworkVariant'], $calculatedParams->getFrameworkVariant());
        $this->assertEquals($leg['calculationParameters']['modality'], $calculatedParams->getModality());
        $this->assertEquals($leg['calculationParameters']['vehicleType'], $calculatedParams->getVehicleType());
        $this->assertEquals($leg['calculationParameters']['cargoType'], $calculatedParams->getCargoType());
    }

    private function getEmptyEmission(): CalculateEmissionRequestDTO
    {
        $calculateEmission = new CalculateEmissionRequestDTO();

        return $calculateEmission->setDestination(new AddressDTO())
            ->setOrigin(new AddressDTO());
    }

    private function errorResponse(int $statusCode): string
    {
        return '{"message":"test", "status":'. $statusCode  .', "path": "test", "timestamp":2023-12-30T10:25:04.486Z"}';
    }

    private function successResponse(): string
    {
        return '{"token_type":"Bearer","expires_in":3599,"ext_expires_in":3599,"access_token":"e"}';
    }

    private function getCredentials(): CredentialsDTO
    {
        return new CredentialsDTO(
            'test',
            'test',
            'test'
        );
    }
}
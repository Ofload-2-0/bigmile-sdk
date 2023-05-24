<?php

namespace Ofload\BigmileSdk\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Ofload\BigmileSdk\Client\BigMileClient;
use Ofload\BigmileSdk\DTOs\CalculatedShipmentDTO;
use Ofload\BigmileSdk\DTOs\CalculateEmissionRequestDTO;
use Ofload\BigmileSdk\DTOs\CredentialsDTO;
use Ofload\BigmileSdk\Exceptions\OauthClientAccessTokenException;
use Ofload\BigmileSdk\Tests\Fixtures\EmissionCalculationFixture;
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
        $calculateEmission = new CalculateEmissionRequestDTO();
        $client->calculateEmission($calculateEmission);
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
        $calculateEmission = new CalculateEmissionRequestDTO();
        $client->calculateEmission($calculateEmission);
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
        $calculateEmission = new CalculateEmissionRequestDTO();
        $client->calculateEmission($calculateEmission);
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
        $calculateEmission = new CalculateEmissionRequestDTO();
        $client->calculateEmission($calculateEmission);
    }

    public function testItShouldCalculateEmission(): void
    {
        $handleStack = HandlerStack::create(
            new MockHandler([
                new Response(
                    BigMileClient::HTTP_OK,
                    [],
                    json_encode(EmissionCalculationFixture::get())
                )
            ])
        );

        $client = new BigMileClient(
            new Client(['handler' => $handleStack])
        );
        $calculateEmission = new CalculateEmissionRequestDTO();
        $response = $client->calculateEmission($calculateEmission);

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
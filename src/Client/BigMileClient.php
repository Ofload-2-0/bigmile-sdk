<?php

declare(strict_types=1);

namespace Ofload\BigMileSdk\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Ofload\BigMileSdk\DTOs\AccessTokenDTO;
use Ofload\BigMileSdk\DTOs\CalculateEmissionRequestDTO;
use Ofload\BigMileSdk\DTOs\CalculateEmissionResponseDTO;
use Ofload\BigMileSdk\DTOs\CredentialsDTO;
use Ofload\BigMileSdk\Exceptions\CalculationEmissionException;
use Ofload\BigMileSdk\Exceptions\OauthClientAccessTokenException;

class BigMileClient
{
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_TOO_MANY_REQUESTS = 429;
    public const HTTP_OK = 200;

    private const ACCESS_TOKEN_ENDPOINT = 'https://login.microsoftonline.com/{{tenant_id}}/oauth2/v2.0/token';
    private const CALCULATE_EMISSION_ENDPOINT = 'https://api.bigmile.eu/emissions/v2/calculate';

    public function __construct(private readonly Client $client)
    {
    }

    public function getAccessToken(CredentialsDTO $credentialsDTO): AccessTokenDTO
    {
        $accessTokenEndpoint = str_replace('{{tenant_id}}', $credentialsDTO->tenantId, static::ACCESS_TOKEN_ENDPOINT);

        try {
            $response = $this->client->post($accessTokenEndpoint, [
                    RequestOptions::FORM_PARAMS => $credentialsDTO->toArray()
                ])
                ->getBody()
                ->getContents();
            $data = json_decode($response, true);

            return AccessTokenDTO::fromArray($data);
        } catch (ClientException $exception) {
            throw new OauthClientAccessTokenException($exception->getMessage(), $exception->getCode());
        }
    }

    public function calculateEmission(
        CalculateEmissionRequestDTO $calculateEmissionDTO,
        AccessTokenDTO $accessTokenDTO
    ): CalculateEmissionResponseDTO {
        try {
            $response = $this->client->post(self::CALCULATE_EMISSION_ENDPOINT, [
                    RequestOptions::JSON => [
                        'calculation' => [
                            $calculateEmissionDTO->toArray()
                        ]
                    ],
                    RequestOptions::HEADERS => [
                        'Authorization' => sprintf('%s %s', $accessTokenDTO->getTokenType(), $accessTokenDTO->getAccessToken())
                    ]
                ])
                ->getBody()
                ->getContents();
            $data = json_decode($response, true);

            return CalculateEmissionResponseDTO::fromArray($data);
        } catch (ClientException $exception) {
            throw new CalculationEmissionException($exception->getMessage(), $exception->getCode());
        }
    }
}

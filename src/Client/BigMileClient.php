<?php

declare(strict_types=1);

namespace Ofload\BigmileSdk\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Ofload\BigmileSdk\DTOs\AccessTokenDTO;
use Ofload\BigmileSdk\DTOs\CalculateEmissionRequestDTO;
use Ofload\BigmileSdk\DTOs\CalculateEmissionResponseDTO;
use Ofload\BigmileSdk\DTOs\CredentialsDTO;
use Ofload\BigmileSdk\Exceptions\CalculationEmissionException;
use Ofload\BigmileSdk\Exceptions\OauthClientAccessTokenException;

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

    public function getAccessToken(CredentialsDTO $valueObject): AccessTokenDTO
    {
        $accessTokenEndpoint = str_replace('{{tenant_id}}', $valueObject->tenantId, static::ACCESS_TOKEN_ENDPOINT);

        try {
            $response = $this->client->post($accessTokenEndpoint, [
                    RequestOptions::FORM_PARAMS => $valueObject->toArray()
                ])
                ->getBody()
                ->getContents();
            $data = json_decode($response, true);

            return AccessTokenDTO::fromArray($data);
        } catch (ClientException $exception) {
            throw new OauthClientAccessTokenException($exception->getMessage(), $exception->getCode());
        }
    }

    public function calculateEmission(CalculateEmissionRequestDTO $calculateEmissionDTO): CalculateEmissionResponseDTO
    {
        try {
            $response = $this->client->post(self::CALCULATE_EMISSION_ENDPOINT, [
                    RequestOptions::JSON => $calculateEmissionDTO->toArray()
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

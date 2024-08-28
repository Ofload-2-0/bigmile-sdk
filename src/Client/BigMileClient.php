<?php

declare(strict_types=1);

namespace Ofload\BigMileSdk\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Ofload\BigMileSdk\DTOs\CalculateEmissionRequestDTO;
use Ofload\BigMileSdk\DTOs\CalculateEmissionResponseDTO;
use Ofload\BigMileSdk\Exceptions\CalculationEmissionException;

class BigMileClient
{
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_TOO_MANY_REQUESTS = 429;
    public const HTTP_OK = 200;
    private const CALCULATE_EMISSION_ENDPOINT = 'https://api.bigmile.eu/emissions/v2/calculate';

    public function __construct(private readonly Client $client)
    {
    }

    public function calculateEmission(
        CalculateEmissionRequestDTO $calculateEmissionDTO,
        string $apiKey
    ): CalculateEmissionResponseDTO {
        try {
            $response = $this->client->post(self::CALCULATE_EMISSION_ENDPOINT, [
                    RequestOptions::JSON => [
                        'calculation' => [
                            $calculateEmissionDTO->toArray()
                        ]
                    ],
                    RequestOptions::HEADERS => [
                        'X-Api-Key' => $apiKey
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

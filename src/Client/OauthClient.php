<?php

declare(strict_types=1);

namespace Ofload\BigmileSdk\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Ofload\BigmileSdk\DTOs\AccessTokenDTO;
use Ofload\BigmileSdk\Support\CredentialsValueObject;
use Ofload\BigmileSdk\Exceptions\OauthClientAccessTokenException;

class OauthClient
{
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_OK = 200;

    private const BASE_URL = 'https://login.microsoftonline.com/';
    private const ACCESS_TOKEN_URLI = '{{tenant_id}}/oauth2/v2.0/token';

    public function __construct(private readonly Client $client)
    {
    }

    public function getAccessToken(CredentialsValueObject $valueObject): AccessTokenDTO
    {
        $tokenUri = str_replace('{{tenant_id}}', $valueObject->tenantId, static::ACCESS_TOKEN_URLI);
        $oauthUrl = sprintf('%s%s', static::BASE_URL, $tokenUri);

        try {
            $response = $this->client->post($oauthUrl, [
                    'form_params' => $valueObject->toArray()
                ])
                ->getBody()
                ->getContents();
            $data = json_decode($response, true);

            return AccessTokenDTO::fromArray($data);
        } catch (ClientException $exception) {
            throw new OauthClientAccessTokenException($exception->getMessage(), $exception->getCode());
        }
    }
}

<?php

namespace Ofload\BigMileSdk\DTOs;

use Ofload\BigMileSdk\Contracts\ArrayableInterface;

class CredentialsDTO implements ArrayableInterface
{
    public function __construct(
        public string $tenantId,
        public string $clientSecret,
        public string $clientId,
        public string $grantType = 'client_credentials'
    ) {
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $this->grantType,
            'scope' => sprintf('%s/.default', $this->clientId)
        ];
    }
}
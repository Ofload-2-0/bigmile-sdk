<?php

declare(strict_types=1);

namespace Ofload\BigMileSdk\DTOs;

class AccessTokenDTO
{
    private string $tokenType;
    private int $expiresIn;
    private int $extExpiresIn;
    private string $accessToken;

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function setTokenType(string $tokenType): AccessTokenDTO
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(int $expiresIn): AccessTokenDTO
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function getExtExpiresIn(): int
    {
        return $this->extExpiresIn;
    }

    public function setExtExpiresIn(int $extExpiresIn): AccessTokenDTO
    {
        $this->extExpiresIn = $extExpiresIn;
        return $this;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): AccessTokenDTO
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        $self = new static();

        return $self->setTokenType($data['token_type'])
            ->setAccessToken($data['access_token'])
            ->setExpiresIn((int) $data['expires_in'])
            ->setExtExpiresIn((int) $data['ext_expires_in']);
    }
}
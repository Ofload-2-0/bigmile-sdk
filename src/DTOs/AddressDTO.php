<?php

declare(strict_types=1);

namespace Ofload\BigMileSdk\DTOs;

use Ofload\BigMileSdk\Contracts\ArrayableInterface;

class AddressDTO implements ArrayableInterface
{
    private ?string $postalCode = null;
    private ?string $city = null;
    private ?string $country = null;
    private ?float $lat = null;
    private ?float $long = null;

    public function getPostalcode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalcode(?string $postalCode): AddressDTO
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): AddressDTO
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): AddressDTO
    {
        $this->country = $country;
        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): AddressDTO
    {
        $this->lat = $lat;
        return $this;
    }

    public function getLong(): ?float
    {
        return $this->long;
    }

    public function setLong(?float $long): AddressDTO
    {
        $this->long = $long;
        return $this;
    }

    public function toArray(): array
    {
        if ($this->getLat() && $this->getLong()) {
            return [
                'latitude' => $this->getLat(),
                'longitude' => $this->getLong()
            ];
        }

        return [
            'postalcode' => $this->getPostalcode(),
            'country' => $this->getCountry(),
            'city' => $this->getCity()
        ];
    }
}
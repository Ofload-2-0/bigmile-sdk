<?php

declare(strict_types=1);

namespace Ofload\BigmileSdk\DTOs;

class AddressDTO
{
    private string $postalCode;
    private string $city;
    private string $country;

    public function getPostalcode(): string
    {
        return $this->postalCode;
    }

    public function setPostalcode(string $postalCode): AddressDTO
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): AddressDTO
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): AddressDTO
    {
        $this->country = $country;
        return $this;
    }
}
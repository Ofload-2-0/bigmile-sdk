<?php

declare(strict_types=1);

namespace Ofload\BigMileSdk\DTOs;

use Ofload\BigMileSdk\Contracts\ArrayableInterface;

class CalculateEmissionRequestDTO implements ArrayableInterface
{
    private string $name;
    private string $type;
    private string $legId;
    private AddressDTO $origin;
    private AddressDTO $destination;
    private string $vehicleType;
    private string $cargoType;
    private string $fuelCategory;
    private mixed $amount;
    private string $unit;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CalculateEmissionRequestDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): CalculateEmissionRequestDTO
    {
        $this->type = $type;
        return $this;
    }

    public function getLegId(): string
    {
        return $this->legId;
    }

    public function setLegId(string $legId): CalculateEmissionRequestDTO
    {
        $this->legId = $legId;
        return $this;
    }

    public function getOrigin(): AddressDTO
    {
        return $this->origin;
    }

    public function setOrigin(AddressDTO $origin): CalculateEmissionRequestDTO
    {
        $this->origin = $origin;
        return $this;
    }

    public function getDestination(): AddressDTO
    {
        return $this->destination;
    }

    public function setDestination(AddressDTO $destination): CalculateEmissionRequestDTO
    {
        $this->destination = $destination;
        return $this;
    }

    public function getVehicleType(): string
    {
        return $this->vehicleType;
    }

    public function setVehicleType(string $vehicleType): CalculateEmissionRequestDTO
    {
        $this->vehicleType = $vehicleType;
        return $this;
    }

    public function getCargoType(): string
    {
        return $this->cargoType;
    }

    public function setCargoType(string $cargoType): CalculateEmissionRequestDTO
    {
        $this->cargoType = $cargoType;
        return $this;
    }

    public function getFuelCategory(): string
    {
        return $this->fuelCategory;
    }

    public function setFuelCategory(string $fuelCategory): CalculateEmissionRequestDTO
    {
        $this->fuelCategory = $fuelCategory;
        return $this;
    }

    public function getAmount(): mixed
    {
        return $this->amount;
    }

    public function setAmount(mixed $amount): CalculateEmissionRequestDTO
    {
        $this->amount = $amount;
        return $this;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): CalculateEmissionRequestDTO
    {
        $this->unit = $unit;
        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
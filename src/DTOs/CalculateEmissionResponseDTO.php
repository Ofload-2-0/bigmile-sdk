<?php

declare(strict_types=1);

namespace Ofload\BigMileSdk\DTOs;

class CalculateEmissionResponseDTO
{
    private array $legs;
    private float|int $totalCO2eWTW;
    private float|int $totalCO2eTTW;

    public function getLegs(): array
    {
        return $this->legs;
    }

    public function setLegs(CalculatedShipmentDTO ...$legs): CalculateEmissionResponseDTO
    {
        $this->legs = $legs;
        return $this;
    }

    public function getTotalCO2eWTW(): float|int
    {
        return $this->totalCO2eWTW;
    }

    public function setTotalCO2eWTW(float|int $totalCO2eWTW): CalculateEmissionResponseDTO
    {
        $this->totalCO2eWTW = $totalCO2eWTW;
        return $this;
    }

    public function getTotalCO2eTTW(): float|int
    {
        return $this->totalCO2eTTW;
    }

    public function setTotalCO2eTTW(float|int $totalCO2eTTW): CalculateEmissionResponseDTO
    {
        $this->totalCO2eTTW = $totalCO2eTTW;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        $self = new static();

        $legs = array_map(function (array $leg) {
            return CalculatedShipmentDTO::fromArray($leg);
        }, $data['legs']);

        return $self->setLegs(...$legs)
            ->setTotalCO2eTTW($data['totalCO2eWTW'])
            ->setTotalCO2eWTW($data['totalCO2eTTW']);
    }
}
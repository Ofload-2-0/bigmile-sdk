<?php

namespace Ofload\BigmileSdk\DTOs;

class CalculatedShipmentDTO
{
    private string $legId;
    private int $CO2eWTW;
    private int $CO2eTTW;
    private CalculatedSourceDTO $calculationParameters;

    public function getLegId(): string
    {
        return $this->legId;
    }

    public function setLegId(string $legId): CalculatedShipmentDTO
    {
        $this->legId = $legId;
        return $this;
    }

    public function getCO2eWTW(): int
    {
        return $this->CO2eWTW;
    }

    public function setCO2eWTW(int $CO2eWTW): CalculatedShipmentDTO
    {
        $this->CO2eWTW = $CO2eWTW;
        return $this;
    }

    public function getCO2eTTW(): int
    {
        return $this->CO2eTTW;
    }

    public function setCO2eTTW(int $CO2eTTW): CalculatedShipmentDTO
    {
        $this->CO2eTTW = $CO2eTTW;
        return $this;
    }

    public function getCalculationParameters(): CalculatedSourceDTO
    {
        return $this->calculationParameters;
    }

    public function setCalculationParameters(CalculatedSourceDTO $calculationParameters): CalculatedShipmentDTO
    {
        $this->calculationParameters = $calculationParameters;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return (new static())
            ->setCalculationParameters(CalculatedSourceDTO::fromArray($data['calculationParameters']))
            ->setLegId($data['legId'])
            ->setCO2eWTW($data['CO2eWTW'])
            ->setCO2eTTW($data['CO2eTTW']);
    }
}
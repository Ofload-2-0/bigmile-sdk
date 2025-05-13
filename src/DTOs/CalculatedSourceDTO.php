<?php

namespace Ofload\BigMileSdk\DTOs;

class CalculatedSourceDTO
{
    private string $lang;
    private string $name;
    private string $type;
    private string $frameworkVariant;
    private string $modality;
    private string $vehicleType;
    private string $cargoType;

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): CalculatedSourceDTO
    {
        $this->lang = $lang;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CalculatedSourceDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): CalculatedSourceDTO
    {
        $this->type = $type;
        return $this;
    }

    public function getFrameworkVariant(): string
    {
        return $this->frameworkVariant;
    }

    public function setFrameworkVariant(string $frameworkVariant): CalculatedSourceDTO
    {
        $this->frameworkVariant = $frameworkVariant;
        return $this;
    }

    public function getModality(): string
    {
        return $this->modality;
    }

    public function setModality(string $modality): CalculatedSourceDTO
    {
        $this->modality = $modality;
        return $this;
    }

    public function getVehicleType(): string
    {
        return $this->vehicleType;
    }

    public function setVehicleType(string $vehicleType): CalculatedSourceDTO
    {
        $this->vehicleType = $vehicleType;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return (new static())
            ->setLang($data['lang'])
            ->setName($data['name'])
            ->setType($data['type'])
            ->setFrameworkVariant($data['frameworkVariant'])
            ->setModality($data['modality'])
            ->setVehicleType($data['vehicleType']);
    }
}

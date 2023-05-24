<?php

namespace Ofload\BigMileSdk\Tests\Fixtures;

class EmissionCalculationFixture
{
    public static function get(): array
    {
        return [
            "legs" => [
                [
                    "legId" => "C101",
                    "CO2eWTW" => 1230,
                    "CO2eTTW" => 640,
                    "calculationParameters" => [
                        "lang" => "en_US",
                        "name" => "NL",
                        "type" => "intensity",
                        "frameworkVariant" => "CargoType VehicleType",
                        "modality" => "Road",
                        "vehicleType" => "Truck >20 t",
                        "cargoType" => "BULK"
                    ]
                ]
            ],
            "totalCO2eWTW" => 1230,
            "totalCO2eTTW" => 640
        ];
    }
}
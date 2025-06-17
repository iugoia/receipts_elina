<?php

namespace App\Services;

use PrinsFrank\Standards\Country\CountryAlpha2;

class CountryService
{
    public function getAllCountries(): array
    {
        $countryCodes = array_column(CountryAlpha2::cases(), 'value');
        $countries = [];
        $countryTranslations = include resource_path('lang/ru/countries.php');
        foreach ($countryCodes as $code) {
            $countries[] = [
                'code' => $code,
                'name' => $countryTranslations[$code] ?? $code
            ];
        }
        return $countries;
    }
}

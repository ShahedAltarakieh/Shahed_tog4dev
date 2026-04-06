<?php

namespace App\Helpers;

use libphonenumber\PhoneNumberUtil;

class PhoneHelper
{
    public static function getPhoneDetails(string $phoneNumber): array
    {
        $phoneNumber = str_replace([' ', "+", "=", "00"], '', $phoneNumber);
        if (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+' . $phoneNumber;
        }

        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $number = $phoneUtil->parse($phoneNumber);
            $countryCode = $phoneUtil->getRegionCodeForNumber($number);
            $country = self::getCountry($countryCode);
        } catch (\libphonenumber\NumberParseException $e) {
            $countryCode = null;
            $country = null;
        }

        return [
            "phone" => $phoneNumber,
            "countryCode" => $countryCode,
            "country" => $country,
        ];
    }

    public static function getCountry(?string $countryCode): ?string
    {
        $filePath = public_path('countries.json');

        if (!file_exists($filePath) || !$countryCode) {
            return null;
        }

        $countriesList = json_decode(file_get_contents($filePath), true);
        $country = collect($countriesList)->firstWhere('country_code', $countryCode);

        return $country['country_name_english'] ?? null;
    }
}

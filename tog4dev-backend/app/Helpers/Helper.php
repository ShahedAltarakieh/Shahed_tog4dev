<?php
namespace App\Helpers;

use App\Models\Category;
use NumberFormatter;

class Helper
{
    protected static $typeMapping = [
        'organization' => 1,
        'projects' => 2,
        'crowdfunding' => 3,
        'home' => 4,
        'ramadan' => -2
    ];

    protected static $shortPaymentName = [
        "ZBOONI" => "Z",
        "CliQ" => "Q",
        "ODOO" => "O",
        "MEPS" => "M",
        "ZBOONI USA" => "ZU",
        "ORANGE MONEY" => "OM",
        "Orange Money" => "OM",
        "Orange money -T4DG" => "OMG",
        "Orange money -T4DK" => "OMK",
        "CASH" => "CSH",
        "BANK" => "BK",
        "Cheque" => "CK",
        "Visa" => "VI"
    ];
    
    public static function getTypes(string $type = null)
    {
        if ($type) {
            return self::$typeMapping[$type] ?? null;
        }

        return self::$typeMapping;
    }

    public static function getPaymentType(string $type = null)
    {
        if ($type !== null) {
            // make input lowercase
            $type = strtolower($type);

            // normalize keys in mapping to lowercase for lookup
            $mapping = array_change_key_case(self::$shortPaymentName, CASE_LOWER);

            return $mapping[$type] ?? null;
        }

        return self::$shortPaymentName;
    }


    public static function getFlipTypes(int $key = null)
    {
        $flip_types = array_flip(self::$typeMapping);
        if ($key) {
            return $flip_types[$key] ?? null;
        }

        return $flip_types;
    }

    public function getCategoriesByType($type)
    {
        return match ($type) {
            'projects' => Category::getProjects()->get(),
            'organization' => Category::getOrganization()->get(),
            'crowdfunding' => Category::getCrowdfunding()->get(),
            'home' => Category::getHome()->get(),
            default => Category::all(), // Fallback if no valid type is provided
        };
    }

   
    function formatNumber($number) {
        // Check if it's a float but has no decimal part
        if (intval($number) == $number) {
            return intval($number); // return as int
        }
        return $number; // return as is (with decimal)
    }
    

    function getSinglePriceQty($single_price, $amount){
        if($single_price != null && $amount > 0){
            return $amount / $single_price;
        }
        return 0;
    }
}

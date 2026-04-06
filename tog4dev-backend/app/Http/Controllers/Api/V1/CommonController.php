<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getCurrency($countryCode)
    {
        // Path to the JSON file in the public folder
        $filePath = public_path('countries.json');

        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Fetch and decode the JSON file
        $countriesList = json_decode(file_get_contents($filePath), true);

        if (!$countryCode) {
            return response()->json(['error' => 'Country code not provided'], 400);
        }

        // Find the country based on the country code
        $country = collect($countriesList)->firstWhere('country_code', $countryCode);

        if ($country) {
            return $country['currency_code'];
        }

        return null;
    }

    public function get_currency($code)
    {
        $jayParsedAry = [
            'JOD' => 1,
            'AED' => 5.17983075,
            'AFN' => 97.49485338,
            'ALL' => 118.76816112,
            'AMD' => 541.42986518,
            'ANG' => 2.52468265,
            'AOA' => 1295.09488852,
            'ARS' => 1785.96614951,
            'AUD' => 2.16904891,
            'AWG' => 2.52468265,
            'AZN' => 2.39865461,
            'BAM' => 2.37533202,
            'BBD' => 2.82087447,
            'BDT' => 171.18336828,
            'BGN' => 2.37533202,
            'BHD' => 0.5303244,
            'BIF' => 4185.21219177,
            'BMD' => 1.41043724,
            'BND' => 1.81239364,
            'BOB' => 9.76788625,
            'BRL' => 7.85938354,
            'BSD' => 1.41043724,
            'BTN' => 121.48656277,
            'BWP' => 19.28006988,
            'BYN' => 4.43311246,
            'BZD' => 2.82087447,
            'CAD' => 1.93798509,
            'CDF' => 4076.08209524,
            'CHF' => 1.13316016,
            'CLP' => 1365.39967419,
            'CNY' => 10.13149225,
            'COP' => 5676.45526198,
            'CRC' => 711.98275102,
            'CUP' => 33.85049365,
            'CVE' => 133.91551654,
            'CZK' => 29.92976753,
            'DJF' => 250.66431594,
            'DKK' => 9.0605418,
            'DOP' => 85.03612525,
            'DZD' => 183.60966523,
            'EGP' => 69.69963296,
            'ERN' => 21.15655853,
            'ETB' => 193.26167775,
            'EUR' => 1.21449017,
            'FJD' => 3.18792808,
            'FKP' => 1.05076625,
            'FOK' => 9.05823347,
            'GBP' => 1.05076718,
            'GEL' => 3.82381069,
            'GGP' => 1.05076625,
            'GHS' => 15.57932032,
            'GIP' => 1.05076625,
            'GMD' => 102.63428288,
            'GNF' => 12249.50614139,
            'GTQ' => 10.82509716,
            'GYD' => 294.94374947,
            'HKD' => 11.07130833,
            'HNL' => 36.9279918,
            'HRK' => 9.15055965,
            'HTG' => 184.92545842,
            'HUF' => 484.61012887,
            'IDR' => 23011.63251636,
            'ILS' => 4.73597526,
            'IMP' => 1.05076625,
            'INR' => 121.48676897,
            'IQD' => 1847.82366148,
            'IRR' => 59623.49154956,
            'ISK' => 172.43715786,
            'JEP' => 1.05076625,
            'JMD' => 225.83331849,
            'JPY' => 209.77925692,
            'KES' => 182.29507526,
            'KGS' => 123.20698068,
            'KHR' => 5660.11590399,
            'KID' => 2.16904132,
            'KMF' => 597.48891638,
            'KRW' => 1962.39337867,
            'KWD' => 0.43115858,
            'KYD' => 1.17536389,
            'KZT' => 752.76713071,
            'LAK' => 30488.47587969,
            'LBP' => 126234.1325811,
            'LKR' => 425.10423677,
            'LRD' => 282.82122191,
            'LSL' => 25.10811506,
            'LYD' => 7.64965222,
            'MAD' => 12.76220733,
            'MDL' => 23.93594968,
            'MGA' => 6246.45023196,
            'MKD' => 75.02144606,
            'MMK' => 2965.00089813,
            'MNT' => 5038.94780185,
            'MOP' => 11.40400389,
            'MRU' => 56.3522867,
            'MUR' => 64.32886399,
            'MVR' => 21.77250647,
            'MWK' => 2462.5275875,
            'MXN' => 26.47068013,
            'MYR' => 5.98746416,
            'MZN' => 89.91909564,
            'NAD' => 25.10811506,
            'NGN' => 2159.08548018,
            'NIO' => 51.91544988,
            'NOK' => 14.48216542,
            'NPR' => 194.37850044,
            'NZD' => 2.37507888,
            'OMR' => 0.54230889,
            'PAB' => 1.41043724,
            'PEN' => 5.01357916,
            'PGK' => 5.85444084,
            'PHP' => 80.68497707,
            'PKR' => 402.55212093,
            'PLN' => 5.16767573,
            'PYG' => 10896.59254382,
            'QAR' => 5.13399154,
            'RON' => 6.16063466,
            'RSD' => 142.24436573,
            'RUB' => 110.27239876,
            'RWF' => 2045.03494906,
            'SAR' => 5.28913963,
            'SBD' => 11.61233777,
            'SCR' => 20.72043092,
            'SDG' => 757.88217082,
            'SEK' => 13.7047565,
            'SGD' => 1.81239437,
            'SHP' => 1.05076625,
            'SLE' => 31.85771794,
            'SLL' => 31857.71797981,
            'SOS' => 805.61038126,
            'SRD' => 52.63010108,
            'SSP' => 6604.51845079,
            'STN' => 29.75495538,
            'SYP' => 18253.21206562,
            'SZL' => 25.10811506,
            'THB' => 45.71949361,
            'TJS' => 13.61093273,
            'TMT' => 4.93535208,
            'TND' => 4.08721303,
            'TOP' => 3.35961612,
            'TRY' => 56.9394231,
            'TTD' => 9.53412285,
            'TVD' => 2.16904132,
            'TWD' => 41.42780554,
            'TZS' => 3648.73491113,
            'UAH' => 59.09477081,
            'UGX' => 5035.3726265,
            'USD' => 1.41043724,
            'UYU' => 57.05725616,
            'UZS' => 17922.39236174,
            'VES' => 166.83695346,
            'VND' => 36792.8814951,
            'VUV' => 168.88329827,
            'WST' => 3.79402777,
            'XAF' => 796.6518885,
            'XCD' => 3.80818054,
            'XCG' => 2.52468265,
            'XDR' => 1.0338854,
            'XOF' => 796.6518885,
            'XPF' => 144.92727901,
            'YER' => 340.59561871,
            'ZAR' => 25.10828637,
            'ZMW' => 32.42738116,
            'ZWL' => 37.80169252
        ]; 

        $currency = $this->getCurrency($code);

        if(isset($jayParsedAry[$currency])){
            return response()->json(['currency' => $jayParsedAry[$currency]], 200);
        }
        
        if($currency){
            $req_url = 'https://v6.exchangerate-api.com/v6/ddee405a830bba561cceacdb/latest/JOD';
            $response_json = file_get_contents($req_url);
            if(false !== $response_json) {
                // Try/catch for json_decode operation
                try {
                    // Decoding
                    $response = json_decode($response_json);
                    // Check for success
                    if('success' === $response->result) {
                        if(isset($response->conversion_rates->$currency)){
                            return response()->json(['currency' => $response->conversion_rates->$currency], 200);
                        }
                    }

                }
                catch(Exception $e) {
                }

            }
        }
    }
}

<?php

if (!function_exists('numberToWords')) {
    function numberToWords($number) {
        $locale = app()->getLocale();
        $f = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
        $integerPart = intval($number); // Extract the integer part
        $fractionalPart = round(fmod($number, 1) * 1000); // Extract the fractional part (Fils)
        $integerWords = $f->format($integerPart) . ($locale == 'ar' ? ' دينار' : ' Dinars'); 
        $fils = ($locale == 'ar' ? ' فلس' : ' Fils');
        $fractionalWords = $fractionalPart > 0 ? $f->format($fractionalPart) . $fils : "";
        $and = ($locale == 'ar' ? ' و ' : ' and ');
        return $integerWords . ($fractionalWords ? $and . $fractionalWords : "");
    }
}

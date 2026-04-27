<?php

if (!function_exists('calculateReadingTime')) {
    function calculateReadingTime(?string $htmlContent): int
    {
        if (empty($htmlContent)) {
            return 1;
        }
        $text = strip_tags($htmlContent);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = trim(preg_replace('/\s+/u', ' ', $text));
        if (empty($text)) {
            return 1;
        }
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = count($words);
        return max(1, (int) ceil($wordCount / 200));
    }
}

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

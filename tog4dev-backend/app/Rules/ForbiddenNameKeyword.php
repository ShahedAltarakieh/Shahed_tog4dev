<?php

namespace App\Rules;

use App\Services\NameValidationService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ForbiddenNameKeyword implements ValidationRule
{
    protected string $locale;

    public function __construct(?string $locale = null)
    {
        $this->locale = $locale ?? app()->getLocale();
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nameValidationService = app(NameValidationService::class);
        if (!$nameValidationService->isNameValid($value)) {
            if($this->locale == "ar"){
                $fail('وفقًا لسياسة معًا، يرجى إدخال اسم كامل وصحيح.');
            } else {
                $fail('According to the "Together" policy, enter a valid full name.');
            }
        }
    }
}

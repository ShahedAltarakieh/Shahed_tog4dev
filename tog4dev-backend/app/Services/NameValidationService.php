<?php

namespace App\Services;

use App\Models\ForbiddenKeyword;

class NameValidationService
{
    /**
     * Check if a name contains forbidden keywords
     *
     * @param string $firstName
     * @param string $lastName
     * @return array
     */
    public function validateNames(string $firstName, string $lastName): array
    {
        $errors = [];

        // Check first name
        if ($this->containsForbiddenKeyword($firstName)) {
            $errors['first_name'] = ['This name contains inappropriate content and cannot be used.'];
        }

        // Check last name
        if ($this->containsForbiddenKeyword($lastName)) {
            $errors['last_name'] = ['This name contains inappropriate content and cannot be used.'];
        }

        return $errors;
    }

    /**
     * Check if a name contains any forbidden keyword
     *
     * @param string $name
     * @return bool
     */
    private function containsForbiddenKeyword(string $name): bool
    {
        $name = strtolower(trim($name));
        
        // Get all forbidden keywords
        $keywords = ForbiddenKeyword::active()->pluck('keyword')->toArray();

        foreach ($keywords as $keyword) {
            $keywordValue = strtolower($keyword);
            
            // Check if the name contains the keyword
            if (str_contains($name, $keywordValue)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a single name is valid
     *
     * @param string $name
     * @return bool
     */
    public function isNameValid(string $name): bool
    {
        return !$this->containsForbiddenKeyword($name);
    }
} 
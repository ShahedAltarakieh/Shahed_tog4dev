<?php

namespace App\Traits;

trait FilterByCategoryType
{
    /**
     * Scope to filter models by category type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByCategoryType($query, $type)
    {
        $typeMap = [
            'organization' => 1,
            'projects' => 2,
            'crowdfunding' => 3,
            'home_only' => -1,
            'ramadan' => -2
        ];

        if (isset($typeMap[$type])) {
            if($type == "home_only"){
                return $query->whereHas('category', function ($q) use ($typeMap, $type) {
                    $q->where('type', 2)->orWhere('type', 3);
                });
            }
            if($type == "ramadan"){
                return $query->whereHas('category', function ($q) use ($typeMap, $type) {
                    $q->where('type', 2)->orWhere('type', 3);
                });
            }
            else {
                return $query->whereHas('category', function ($q) use ($typeMap, $type) {
                    $q->where('type', $typeMap[$type]);
                });
            }
        }

        return $query; // Return unfiltered query if no valid type is provided
    }
}

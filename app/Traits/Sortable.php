<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * Apply sorting to the query based on request parameters.
     *
     * @param Builder $query
     * @param array $allowedColumns
     * @param string $defaultColumn
     * @param string $defaultDirection
     * @return Builder
     */
    public function applySorting(Builder $query, array $allowedColumns, $defaultColumn = 'created_at', $defaultDirection = 'desc')
    {
        $sort = request('sort');
        $direction = request('direction', $defaultDirection);

        // Ensure the direction is strictly 'asc' or 'desc'
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        // Securely check if the requested column is in the allowed list
        if ($sort && in_array($sort, $allowedColumns)) {
            // Check if column has a relation (e.g. 'category.name')
            // For now, assume single table columns or raw queries if needed.
            // Advanced sorting (like joins) should be handled manually in the controller.
            return $query->orderBy($sort, $direction);
        }

        // Apply default sort if no valid sorting param is provided
        return $query->orderBy($defaultColumn, $defaultDirection);
    }
}

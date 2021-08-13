<?php declare(strict_types=1);

namespace App\Domain\Traits;

trait Searchable
{
    public function scopeByUserSearch($query, ?string $terms = null, ?array $fields = null)
    {
        if (! is_null($terms) || is_null($fields)) {
            return $query->where(function ($q) use ($terms, $fields) {
                $count = 0;
                $q->where($fields[$count], 'LIKE', '%'.$terms.'%');
                while (++$count < count($fields)) {
                    $q->orWhere($fields[$count], 'LIKE', '%'.$terms.'%');
                }
            });
        }

        return $query;
    }
}

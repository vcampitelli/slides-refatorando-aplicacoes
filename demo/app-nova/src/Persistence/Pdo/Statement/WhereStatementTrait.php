<?php

declare(strict_types=1);

namespace App\Persistence\Pdo\Statement;

trait WhereStatementTrait
{
    /**
     * @param array $criteria
     * @return array
     */
    protected function buildWhere(array $criteria): array
    {
        if (empty($criteria)) {
            return [null, null];
        }

        $placeholders = [];
        foreach ($criteria as $clausule => $criterion) {
            if (\is_array($criterion)) {
                foreach ($criterion as $value) {
                    $placeholders[] = $value;
                }
                // Fazendo replace de cláusulas que sejam como: id IN (?) => [1, 2, 3]
                // para: id IN (?, ?, ?) => [1, 2, 3]
                if (\substr_count($clausule, '?') === 1) {
                    $count = count($criterion);
                    if ($count > 1) {
                        $new = \str_replace(
                            '?',
                            \trim(\str_repeat('?,', $count), ','),
                            $clausule
                        );
                        unset($criteria[$clausule]);
                        $criteria[$new] = $criterion;
                    }
                }
                continue;
            }
            $placeholders[] = $criterion;
        }

        return [
            ' WHERE ' . \implode(' AND ', \array_keys($criteria)),
            $placeholders
        ];
    }
}

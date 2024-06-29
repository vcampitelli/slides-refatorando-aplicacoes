<?php

declare(strict_types=1);

namespace App\Persistence\Pdo\Statement;

use App\Persistence\Pdo\PdoStatementException;

class InsertPdoStatement extends AbstractPdoStatement
{

    /**
     * @param string $table
     * @param array  $values
     *
     * @return string
     */
    public function __invoke(
        string $table,
        array $values,
    ): string {
        $query = \sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->pdo->quoteIdentifier($table),
            implode(', ', \array_map([$this->pdo, 'quoteIdentifier'], \array_keys($values))),
            \rtrim(\str_repeat('?,', count($values)), ',')
        );

        $statement = $this->pdo->prepare($query);
        if ((!$statement) || (!$statement->execute(\array_values($values)))) {
            throw new PdoStatementException('Error executing INSERT statement', $statement);
        }

        $id = $this->pdo->lastInsertId();
        if (empty($id)) {
            throw new PdoStatementException('Error retrieving last inserted ID', $statement);
        }

        return $id;
    }
}

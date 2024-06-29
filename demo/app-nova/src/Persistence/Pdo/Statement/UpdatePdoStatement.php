<?php

declare(strict_types=1);

namespace App\Persistence\Pdo\Statement;

use App\Persistence\Pdo\PdoStatementException;
use PDOException;

class UpdatePdoStatement extends AbstractPdoStatement
{
    use WhereStatementTrait;

    /**
     * @param string $table
     * @param array  $values
     * @param array  $where
     *
     * @return int
     */
    public function __invoke(string $table, array $values, array $where): int
    {
        $bind = \array_values($values);

        $sql = 'UPDATE ' . $this->pdo->quoteIdentifier($table) . ' SET ';
        $columns = [];
        foreach (\array_keys($values) as $column) {
            $columns[] = $this->pdo->quoteIdentifier($column) . ' = ?';
        }
        $sql .= \implode(', ', $columns);

        [$condition, $placeholders] = $this->buildWhere($where);
        if (!empty($condition)) {
            $sql .= $condition;
        }
        if (!empty($placeholders)) {
            $bind = \array_merge($bind, $placeholders);
        }

        $statement = $this->pdo->prepare($sql);
        if (!$statement) {
            throw new PdoStatementException('Error executing UPDATE statement');
        }

        try {
            $statement->execute($bind);
        } catch (PDOException $e) {
            throw new PdoStatementException(
                'Erro ao executar UPDATE',
                $statement,
                $e
            );
        }

        return $statement->rowCount();
    }
}

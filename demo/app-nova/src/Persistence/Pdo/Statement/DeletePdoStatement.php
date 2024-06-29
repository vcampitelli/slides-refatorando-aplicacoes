<?php

declare(strict_types=1);

namespace App\Persistence\Pdo\Statement;

use App\Persistence\Pdo\PdoStatementException;
use PDOException;

class DeletePdoStatement extends AbstractPdoStatement
{
    use WhereStatementTrait;

    /**
     * @param array  $values
     * @param string $table
     *
     * @return void
     */
    public function __invoke(array $values, string $table): void
    {
        $sql = 'DELETE FROM ' . $this->pdo->quoteIdentifier($table);
        $placeholders = [];
        if (!empty($criteria)) {
            [$where, $placeholders] = $this->buildWhere($criteria);
            if (!empty($where)) {
                $sql .= $where;
            }
        }
        $statement = $this->pdo->prepare($sql);
        try {
            $statement->execute($placeholders);
        } catch (PDOException $e) {
            throw new PdoStatementException(
                'Erro ao executar DELETE',
                $statement,
                $e
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Persistence\Pdo\Statement;

use App\Persistence\Pdo\PdoStatementException;
use Generator;
use PDOException;
use stdClass;

class SelectPdoStatement extends AbstractPdoStatement
{
    use WhereStatementTrait;

    /**
     * @param string     $table
     * @param array|null $criteria
     * @param int        $limit
     * @param int        $page
     *
     * @return Generator<stdClass>
     */
    public function __invoke(
        string $table,
        array $criteria = null,
        int $limit = 0,
        int $page = 1
    ): Generator {
        // Querying the database
        $sql = 'SELECT * FROM ' . $this->pdo->quoteIdentifier($table);
        $placeholders = [];
        if (!empty($criteria)) {
            [$where, $placeholders] = $this->buildWhere($criteria);
            if (!empty($where)) {
                $sql .= $where;
            }
        }
        if ($limit > 0) {
            $sql .= ($page > 1)
                ? (' LIMIT ' . ($page - 1) * $limit . ", {$limit}")
                : " LIMIT {$limit}";
        }

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($placeholders);
        } catch (PDOException $e) {
            throw new PdoStatementException(
                'Erro ao executar SELECT',
                $statement,
                $e
            );
        }

        while (($row = $statement->fetch(\PDO::FETCH_OBJ)) !== false) {
            /** @var stdClass $row */
            yield $row;
        }
    }
}

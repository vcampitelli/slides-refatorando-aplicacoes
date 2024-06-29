<?php

declare(strict_types=1);

namespace App\Persistence\Pdo;

class PdoStatementException extends \RuntimeException
{
    public readonly ?string $errorCode;

    /**
     * @var string[]
     */
    public readonly array $errorInfo;

    public function __construct(string $message, \PDOStatement $statement = null, \PDOException $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->errorCode = ($statement) ? $statement->errorCode() : null;
        $this->errorInfo = ($statement) ? $statement->errorInfo() : [];
    }
}

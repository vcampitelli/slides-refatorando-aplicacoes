<?php

declare(strict_types=1);

namespace App\Persistence\Pdo;

use PDO;

class PdoWrapper extends PDO
{
    public function quoteIdentifier(string $identifier): string
    {
        switch ($this->getAttribute(PDO::ATTR_DRIVER_NAME)) {
            case 'sqlite':
                $char = "'";
                $quote = "''";
                break;

            case 'mysql':
                $char = '`';
                $quote = '`';
                break;

            default:
                return $this->quote($identifier);
        }

        return $char . \str_replace($char, $quote, $identifier) . $char;
    }
}

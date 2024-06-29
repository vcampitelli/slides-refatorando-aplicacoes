<?php

declare(strict_types=1);

namespace App\Persistence\Pdo\Statement;

use App\Persistence\Pdo\PdoWrapper;

abstract class AbstractPdoStatement
{
    /**
     * @param PdoWrapper $pdo
     */
    public function __construct(protected readonly PdoWrapper $pdo)
    {
    }
}

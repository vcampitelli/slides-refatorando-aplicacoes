<?php

declare(strict_types=1);

namespace App\Collection;

interface CollectionInterface extends \IteratorAggregate
{
    public function add(object $object): void;
}

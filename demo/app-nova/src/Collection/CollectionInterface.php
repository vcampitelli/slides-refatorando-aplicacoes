<?php

declare(strict_types=1);

namespace App\Collection;

use IteratorAggregate;
use JsonSerializable;

/**
 * @template T of object
 */
interface CollectionInterface extends IteratorAggregate, JsonSerializable
{
    /**
     * @param T $object
     * @return void
     */
    public function add(object $object): void;
}

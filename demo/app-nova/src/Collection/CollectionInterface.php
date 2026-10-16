<?php

declare(strict_types=1);

namespace App\Collection;

use IteratorAggregate;
use JsonSerializable;

/**
 * @template-covariant T of object
 * @extends IteratorAggregate<int, T>
 */
interface CollectionInterface extends IteratorAggregate, JsonSerializable
{
    /**
     * @param object $object
     * @return void
     */
    public function add(object $object): void;
}

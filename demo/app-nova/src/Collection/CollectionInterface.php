<?php

declare(strict_types=1);

namespace App\Collection;

/**
 * @template T of object
 */
interface CollectionInterface extends \IteratorAggregate
{
    /**
     * @param T $object
     * @return void
     */
    public function add(object $object): void;
}

<?php

namespace App\Application\EventStore\Domain;

use Iterator;

class EventStoreCollection implements Iterator
{
    private int $index = 0;
    private array $data = [];

    public function append(EventStore $eventStore) : void
    {
        $this->data[] = $eventStore;
    }

    public function current(): EventStore
    {
        return $this->data[$this->index];
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return array_key_exists($this->index, $this->data);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}

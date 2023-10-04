<?php

namespace App\Application\Event\Domain;

use Iterator;

class EventDayCollection implements Iterator
{
    private int $index = 0;
    private array $data = [];

    public function append(EventDay $eventDay) : void
    {
        $this->data[] = $eventDay;
    }

    public function current(): EventDay
    {
        return $this->data[$this->index];
    }
   
    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        $this->index++;
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function valid(): bool
    {
        return array_key_exists($this->index, $this->data);
    }
}

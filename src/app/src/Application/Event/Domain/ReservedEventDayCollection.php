<?php

namespace App\Application\Event\Domain;

use Iterator;

class ReservedEventDayCollection implements Iterator
{
    private int $index = 0;
    
    /**
     * @var ReservedEventDay[] $data
     */
    private array $data = [];

    public function append(ReservedEventDay $eventDay) : void
    {
        $this->data[] = $eventDay;
    }

    public function current(): ReservedEventDay
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

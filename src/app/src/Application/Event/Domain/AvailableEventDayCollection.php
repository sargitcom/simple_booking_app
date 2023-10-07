<?php

namespace App\Application\Event\Domain;

use Iterator;

class AvailableEventDayCollection implements Iterator
{
    private int $index = 0;
    
    /**
     * @var AvailableEventDay[] $data
     */
    private array $data = [];

    public function reduceSeatsNumber(int $seatsNumber) : void
    {
        foreach ($this->data as $key => $seat) {
            $this->data[$key] = $seat->reduceSeatsNumber(EventDaySeats::create($seatsNumber));
        }
    }

    public function append(AvailableEventDay $eventDay) : void
    {
        $this->data[] = $eventDay;
    }

    public function count() : int
    {
        return count($this->data);
    }

    public function current(): AvailableEventDay
    {
        return $this->data[$this->index];
    }
   
    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        ++$this->index;
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

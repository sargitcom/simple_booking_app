<?php

namespace App\Application\Event\Domain;

class EventDayPrice
{
    private float $price;

    private function __construct(float $price)
    {
        $this->assertPriceNonNegative($price);
        $this->setPrice($price);
    }

    public static function create(float $price) : self
    {
        return new self($price);
    }
 
    protected function assertPriceNonNegative(float $price) : void
    {
        if ($price >= 0) {
            return;
        }

        throw new EventDayPriceNegativeException($price);
    }

    protected function setPrice(float $price) : self
    {
        $this->price = $price;
        return $this;
    }

    public function getPrice() : float
    {
        return $this->price;
    }
}

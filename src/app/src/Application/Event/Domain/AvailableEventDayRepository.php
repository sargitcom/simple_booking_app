<?php

namespace App\Application\Event\Domain;

interface AvailableEventDayRepository
{
    public function save(AvailableEventDayCollection $collection);
}

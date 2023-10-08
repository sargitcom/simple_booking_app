<?php

namespace App\Application\Event\Domain;

class Page
{
    private int $page;

    private function __construct(int $page)
    {
        $this->assertPageNotInMinus($page);
        $this->setPage($page);
    }

    public static function create(int $page) : self
    {
        return new self($page);
    }

    protected function assertPageNotInMinus(int $page) : void
    {
        if ($page > 0) {
            return;
        }

        throw new PageInMinusException($page);
    }

    protected function setPage($page) : void
    {
        $this->page = $page;
    }

    public function getPage() : int
    {
        return $this->page;
    }
}

<?php

namespace App\Application\Event\Domain;

class AgreggateVersion
{
    private int $version;

    private function __construct(int $version)
    {
        $this->assertversionNonNegative($version);
        $this->setversion($version);
    }

    public static function create() : self
    {
        return new self(1);
    }
 
    public function inc() : self
    {
        return new self($this->getVersion() + 1);
    }

    protected function assertVersionNonNegative(int $version) : void
    {
        if ($version >= 0) {
            return;
        }

        throw new AgreggateVersionIsNegativeException($version);
    }

    protected function setVersion(int $version) : self
    {
        $this->version = $version;
        return $this;
    }

    public function getVersion() : int
    {
        return $this->version;
    }
}

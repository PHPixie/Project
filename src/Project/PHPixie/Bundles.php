<?php

namespace Project;

class Bundles extends \PHPixie\Framework\Bundles
{
    protected function buildHello()
    {
        return new \Project\Hello($this->);
    }
}
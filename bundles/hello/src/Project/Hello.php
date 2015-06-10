<?php

namespace Project;

class Hello extends \PHPixie\Framework\Bundle\Implementation
{
    public function rootDirectory()
    {
        return realpath(__DIR__.'/../../');
    }
}
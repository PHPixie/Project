<?php

namespace Project\App;

class Builder extends \PHPixie\DefaultBundle\Builder
{
    protected function buildHttpProcessor()
    {
        return new HTTPProcessor($this);
    }
    
    protected function getRootDirectory()
    {
        return realpath(__DIR__.'/../../../');
    }
}
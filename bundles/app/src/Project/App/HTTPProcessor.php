<?php

namespace Project\App;

class HTTPProcessor extends \PHPixie\DefaultBundle\Builder
{
    protected function buildHelloProcessor()
    {
        new HTTPProcessors\Hello();
    }
}
<?php

namespace Project\App;

class Builder extends \PHPixie\DefaultBundle\Builder
{
    protected function httpProcessor()
    {
        return new HTTPPRocessor();
    }
}
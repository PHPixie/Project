<?php

namespace Project\App;

/**
 * App bundle builder
 */
class Builder extends \PHPixie\DefaultBundle\Builder
{
    /**
     * Build Processor for HTTP requests
     * @return HTTPProcessor
     */
    protected function buildHttpProcessor()
    {
        return new HTTPProcessor($this);
    }

    /**
     * Build ORM Wrappers
     * @return ORMWrappers
     */
    protected function buildORMWrappers()
    {
        return new ORMWrappers($this);
    }

    /**
     * Build Console command registry
     * @return ConsoleCommands
     */
    protected function buildConsoleProvider()
    {
        return new Console($this);
    }
    
    /**
     * Get bundle root directory
     * @return string
     */
    protected function getRootDirectory()
    {
        return realpath(__DIR__.'/../../../');
    }

    /**
     * Get bundle name
     * @return string
     */
    public function bundleName()
    {
        return 'app';
    }
}
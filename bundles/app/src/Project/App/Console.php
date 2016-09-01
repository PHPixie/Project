<?php

namespace Project\App;

class Console extends \PHPixie\DefaultBundle\Console
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Constructor
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }
    
    public function commandNames()
    {
        return array('greet');
    }
    
    /**
     * Build 'greet' command
     * @return ConsoleCommands\Greet
     */
    protected function buildGreetCommand($commandConfig)
    {
        return new Console\Greet($commandConfig);
    }
}
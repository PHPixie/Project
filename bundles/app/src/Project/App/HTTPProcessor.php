<?php

namespace Project\App;

class HTTPProcessor extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
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

    /**
     * Build 'greet' processor
     * @return HTTPProcessors\Greet
     */
    protected function buildGreetProcessor()
    {
        $components = $this->builder->components();
        
        return new HTTPProcessors\Greet(
            $components->template()    
        );
    }
}
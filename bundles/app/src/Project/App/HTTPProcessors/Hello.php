<?php

namespace Project\App\Processors\HTTP;

class Hello extends PHPixie\DefaultBundle\Processor\HTTP\Actions
{
    protected $builder;
    
    public function __construct($builder)
    {
        $this->builder = $builder;
    }
    
    public function process($request)
    {
        $template = $this->template->container('app:hello');
        $template->message = "Have fun coding!";
        return $template;
    }
}
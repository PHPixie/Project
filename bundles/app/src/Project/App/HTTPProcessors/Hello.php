<?php

namespace Project\App\HTTPProcessors;

class Hello extends \PHPixie\DefaultBundle\Processor\HTTP\Actions
{
    protected $template;
    protected $attribute = 'action';
    
    public function __construct($template)
    {
        $this->template = $template;
    }
    
    public function greetAction($request)
    {
        $container = $this->template->get('app:hello/greet');
        $container->message = "Have fun coding!";
        return $container;
    }
}
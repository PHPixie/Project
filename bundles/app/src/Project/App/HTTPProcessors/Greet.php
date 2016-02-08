<?php

namespace Project\App\HTTPProcessors;

use PHPixie\HTTP\Request;
use PHPixie\Template;

class Greet extends \PHPixie\DefaultBundle\Processor\HTTP\Actions
{
    /**
     * @var Template Template component
     */
    protected $template;

    /**
     * Constructor
     * @param Template $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * Default action
     * @param Request $request HTTP request
     * @return Template\Container
     */
    public function defaultAction($request)
    {
        $container = $this->template->get('app:greet');
        $container->message = "Have fun coding!";
        return $container;
    }
}
<?php

namespace Project;

class App extends \PHPixie\DefaultBundle
{
    protected function buildBuilder($frameworkBuilder)
    {
        return new App\Builder($frameworkBuilder);
    }
    
    public function name()
    {
        return 'app';
    }
}
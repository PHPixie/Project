<?php

namespace Project\App\Console;

class Greet extends \PHPixie\Console\Command\Implementation
{
    public function __construct($config)
    {
        $config
            ->description('Greet the user');
        
        $config->argument('message')
            ->description("Message to display");
        
        parent::__construct($config);
    }
    
    public function run($argumentData, $optionData)
    {
        $message = $argumentData->get('message', "Have fun coding!");
        $this->writeLine($message);
    }
}
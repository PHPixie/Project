<?php

return array(
    'type'     => 'prefix',
    'locators' => array(
        
        //templates locator
        'templates' => array(
            array(
                'type'     => 'prefix',
                'locators' => array(
                    
                    //Mount Hello templates
                    'hello' => array(
                        'type' => 'mount',
                        'name' => 'hello:templates'
                    ),
                    
                )
            )
        )
        //end templates locator
    )
);
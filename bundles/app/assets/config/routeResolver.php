<?php

return array(
    'type'      => 'group',
    'resolvers' => array(
        
        'default' => array(
            'type'     => 'pattern',
            'path'     => '(<processor>(/<action>))',
            'defaults' => array(
                'processor' => 'hello',
                'action'    => 'greet'
            )
        )
        
    )
);
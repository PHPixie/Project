<?php

return array(
    'migrations' => array(
        'default' => array(
            
            // database connection name
            'connection' => 'default',
            
            //relative to the /assets/migrate/ folder
            'path'       => 'migrations'
        )
    ),
    'seeds' => array(
        'default' => array(
            
            // database connection name
            'connection' => 'default',
            
            //relative to the /assets/migrate/ folder
            'path' => 'seeds'
        )
    )
);
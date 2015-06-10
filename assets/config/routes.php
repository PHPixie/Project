<?php

return array(
    'translator' => array(
        'basePath' => '/'
    ),
    'resolver' => array(
        'type'      => 'group',
        'resolvers' => array(
            array(
                'type' => 'mount',
                'name' => 'app'
            )
        )
    )
);
<?php

return array(
    'type'     => 'pattern',
    'path'     => '(<processor>(/<action>))',
    'defaults' => array(
        'processor' => 'hello',
        'action'    => 'greet'
    )
);
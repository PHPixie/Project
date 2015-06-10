<?php

return array(
    array(
        'type'     => 'pattern',
        'url'      => '(/<processor>(/<action>))',
        'defaults' => array(
            'processor' => 'hello',
            'action'    => 'index'
        )
    )
);
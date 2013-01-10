<?php

return array(
	'routes' => array(
		array('default', '(/<controller>(/<action>(/<id>)))', array(
				'controller' => 'home',
				'action' => 'index'
			)
		)
	),
	'modules' => array('database', 'orm','email')
);

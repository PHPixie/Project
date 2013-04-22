<?php

return array(
	'default' => array(
		'user'=>'root',
		'password' => '',
		'driver' => 'pdo',
		
		//'Connection' is required if you use the PDO driver
		'connection'=>'mysql:host=localhost;dbname=pixies',
		
		// 'db' and 'host' are required if you use Mysql driver
		'db' => 'pixies',
		'host'=>'localhost'
	)
);

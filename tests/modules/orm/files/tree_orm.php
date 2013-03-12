<?php

class Tree_Model extends ORM
{

	public $has_one = array('fairy');
	public $connection = 'orm';
	public $belongs_to = array(
		'protector' => array('model' => 'fairy', 'key' => 'protector_id')
	);

}

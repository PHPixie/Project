<?php
class Test_ORM extends ORM {
	public $belongs_to=array('test');
	public $has_one=array('btest'=>array('model'=>'test'));
	public $has_many = array(
		'tests'=>array('model'=>'test','key'=>'has_key'),
		'teststhrough'=>array('model'=>'test','through'=>'test_through')
	);
}
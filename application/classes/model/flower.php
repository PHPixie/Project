<?php
class Flower_Model extends ORM{
	public $has_many=array('fairies');
	public $belongs_to=array('protector'=>array('model'=>'fairy','key'=>'protector_id'));
}
<?php
class Option_Model extends ORM{
	public $belongs_to = array('poll');
	
	public function get($property) {
		if ($property == 'percent') {
			if($this->poll->total_votes==0)
				return 0;
			return floor($this->votes/$this->poll->total_votes*100);
		}
	}
}
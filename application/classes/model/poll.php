<?php
class Poll_Model extends ORM{
	public $has_many=array('options');
	
	public function get($property){
		if ($property == 'total_votes') {
			$total=0;
			foreach($this->options->find_all() as $option)
				$total += $option->votes;
			return $total;
		}
	}
}
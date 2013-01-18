<?php
class Home_Controller extends Controller {

	public function action_index(){
		foreach(ORM::factory('fairy')->with ('tree.protector','tree.flower.protector')->find_all() as $fairy) {
			echo $fairy->tree->protector->name;
		
		}
	}
		
}
?>
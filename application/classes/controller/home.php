<?php
class Home_Controller extends Controller {

	public function action_index(){
		print_r(ORM::factory('fairy')->with('tree')->find_all()->as_array(true));
	}
		
}
?>
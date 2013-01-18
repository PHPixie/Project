<?php
class Home_Controller extends Controller {

	public function action_index(){
		$view = View::get('home');
		$view->message = 'Have fun coding!';
		$this->response->body=$view->render();
	}
		
}
?>
<?php
class Home_Controller extends Controller {

	public function action_index(){
		$view = View::get('home');
		$view->message = 'Have fun coding!';
		Cache::set('as1d', array(1, 2, 3, 4, 5), 100);
		Cache::set('as2d', array(1, 2, 3, 4, 5), 100);
		Cache::set('asd3', array(1,2,3,4,5),100);
		Cache::garbage_collect();
		print_r(DB::query('select')->table('cache')->execute()->as_array());
		Cache::delete_all('asd3');
		Cache::instance()->set('as1d', array(1, 2, 3, 4, 5), -100);
		echo Cache::instance()->set('as1d','huj');
		$this->response->body=$view->render();
	}
		
}
?>
<?php
namespace App\Controller;

class Fairies extends \App\Page {

	public function action_index(){
		$this->view->subview = 'list';
		$this->view->fairies = $this->pixie->orm->get('fairy')->find_all();
	}
	
	public function action_view(){
		$this->view->subview = 'view';
		$id = $this->request->param('id');
		$this->view->fairy = $this->pixie->orm->get('fairy', $id);
	}
	
}
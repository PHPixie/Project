<?php
class Polls_Controller extends Controller {

	public $view;
	public $template;
	
	public function before() {
		$this->view = View::get('main');
		$this->template=$this->request->param('action');
	}
	public function action_index(){
		$this->view->polls = ORM::factory('poll')->find_all();
	}
	
	public function action_poll() {

		if ($this->request->method == 'POST') {
			$option_id = $this->request->post('option');
			$option = ORM::factory('option')->where('id', $option_id)->find();
			$option->votes += 1;
			$option->save();
			$this->response-> redirect('/polls/poll/'.$option->poll->id);
			$this->execute=false;
			return;
		}
		
		$id=$this->request->param('id');
		$this->view->poll = ORM::factory('poll')->where('id', $id)->find();
				
	}
	
	public function action_add(){
		if ($this->request->method == 'POST') {
			$poll = ORM::factory('poll');
			$poll->question = $this->request->post('question');
			$poll->save();
			foreach($this->request->post('options') as $text) {
				if (empty($text))
					continue;
				$option = ORM::factory('option');
				$option->text = $text;
				$option->save();
				$poll->add('options',$option);
			}
			$this->response->redirect('/polls/');
			return;
		}
		
		$this->template='add';
	}
	
	public function after() {
		$this->view->template=Misc::find_file('views',$this->template);
		$this->response->body=$this->view->render();
	}
	
		
}
?>
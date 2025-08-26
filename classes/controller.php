<?php
class controller {
	
	protected $request;
	protected $result;
	protected $response;
	
	public function __construct() {
		if (isset($_POST['isAjax']) && $_POST['isAjax']) {
			$this->isAjax = true;
		} else {
			$this->isAjax = false;
		}
	}
	public function workflow(){
		$this->getRequested();
		$this->validateRequest();
		$this->showResponseIndex();
	}
	
	private function getRequested(){
		if ($this->isAjax){
			$this->handleAjaxRequest();
		}
		else {
			$posted = ($_SERVER['REQUEST_METHOD'] ==='POST');
			if ($posted){
				$page = isset($_POST['page']) ? $_POST['page'] : 'home';
				$action = 'POST';
			}
			else{
				$page = isset($_GET['page']) ? $_GET['page'] : 'home';
				$action = 'GET';
			}
			$this->request = ['isAjax'=> false, 'action'=>$action, 'page' => $page];
		}
	}
	private function validateRequest(){
		$this->result = $this->request;
		$validator = new validator($this->result);
		$this->result = $validator->validateRequest();
	}
	private function showResponseIndex(){
		$this->response = $this->result;
		$Response = new Response($this->response);
		$Response->respond();
	}
	///////////////////////handleAjaxRequest///////////////////////
	private function handleAjaxRequest(){
		$ajaxType = $_POST['action']? $_POST['action']: '';
		switch ($ajaxType){
			case 'nextLevel':
			$this->request = ['isAjax'=>true, 'action'=>'nextLevel', 'page'=>'game'];
			$_SESSION['currentLevel'] += 1;
			break;
			default:
			$this->request = ['isAjax'=>true, 'action'=>$_POST['action'], 'page'=>$_POST['page']];
		}
	} 
	///////////////////////handlePostRequest///////////////////////
	private function handlePostRequest(){
		
	}
	///////////////////////handleGetRequest///////////////////////
	private function handleGetRequest(){
		
	}
}
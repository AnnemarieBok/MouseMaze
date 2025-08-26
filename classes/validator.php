<?php 
class validator{
	private $result;
	private $conn;
	public function __construct(array $result){
		$this->result = $result;
	}
	public function validateRequest(){
		if ($this->result['isAjax']){
			$this->handleAjaxRequest();
		}
		else if ($this->result['action']=='POST'){
			$this->handlePostRequest();
		}
		else if ($this->result['action'] == 'GET'){
			$this->handleGetRequest();
		}
		return $this->result;
	}
	
	private function handleAjaxRequest(){
		if ($_SESSION['currentLevel'] > $_SESSION['nLevels']) {
			$_SESSION['currentLevel'] = 1;
			$this->result = [
				'isAjax' => true,
				'action' => 'loadPage',
				'page'   => 'success'
			];
		}
	}
	private function handlePostRequest(){
	}
	private function handleGetRequest(){
	}
}
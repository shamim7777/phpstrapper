<?php
class Controllers_Error extends RestController{
 
	public function get() { 
		 $db = $this->db;

		$this->template->show('header');
		$this->template->show('error');
		$this->template->show('footer');
	   exit();	
	}
	public function post() {
		$this->response = array('TestResponse' => 'I am POST response. Variables sent are - ' . http_build_query($this->request['params']));
		$this->responseStatus = 201;
	}
	public function put() {
		$this->response = array('TestResponse' => 'I am PUT response. Variables sent are - ' . http_build_query($this->request['params']));
		$this->responseStatus = 200;
	}
	public function delete() {
		$this->response = array('TestResponse' => 'I am DELETE response. Variables sent are - ' . http_build_query($this->request['params']));
		$this->responseStatus = 200;
	}
}

<?php
/**
 * 
 * @author 王涛
 * 用户
 *
 */
class MemberClass extends Page{
	public function add(){
		
		$this->view();
	}
	public function del(){
	
		$this->view();
	}
	public function edit(){
	
		$this->view();
	}
	public function memberlist(){
	
		$this->view('list');
	}
	public function password(){
	
		$this->view();
	}
}
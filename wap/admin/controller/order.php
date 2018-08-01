<?php
/**
 * 
 * @author 王涛
 * 订单
 *
 */
class OrderClass extends Page{
	public function add(){
		$this->view();
	}
	public function orderlist(){
		$this->view('list');
	}
}
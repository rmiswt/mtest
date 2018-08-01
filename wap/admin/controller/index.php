<?php
/**
 * 
 * @author 王涛
 * 首页
 *
 */
class IndexClass extends Page{
	public function index(){
		$rules = $_SESSION['userinfo']['rules'];
		$menus = array();
		foreach ($rules as $rule){
			if($rule['type'] == 0){ //菜单列表显示
				$menus[$rule['modulename']][] = $rule;
			}
		}
		$this->assign('menus', $menus);
		$this->view();
	}
	public function welcome(){
		$this->view();
	}
}
<?php
/****************
 * 核心文件
 * @discription: 编写简单的模板引擎
 */
include_once FROOT.'/common/template.php';
class Page extends Template {
	function __construct(){
		//判断是否有权限访问
		parse_str($_SERVER['QUERY_STRING'],$query);
		$querys = explode('/', $query['act']);
		$ca = getCA($querys);
		$white = array('auth/login','auth/logout','auth/clear'); //白名单
		if(!in_array($ca['c'].'/'.$ca['a'],$white)){
			if(!isset($_SESSION['userinfo']) || $_SESSION['userinfo']['expiretime']<time()){
				unset($_SESSION['userinfo']);
				parentTo("/admin/login.html");
			}
			$_SESSION['userinfo']['expiretime']= time()+ETIME;
			//首页不做判断
			$waddr = array('index/index','index/welcome');
			if(!in_array($ca['c'].'/'.$ca['a'],$waddr)){
				if(!isset($_SESSION['userinfo']['isadmin']) || $_SESSION['userinfo']['isadmin'] == 0){
					$rules = $_SESSION['userinfo']['rules'];
					if(is_array($rules)){ //限制访问
						$flag = false;
						foreach ($rules as $rule){
							$erule = explode('/', $rule['rule']);
							if($erule[0] == $ca['c'] && $erule[1] == $ca['a']){ //没权限访问
								$flag = true;
								break;
							}
						}
						if(!$flag){
							parentTo("/admin/index/index.html");
						}
					}
				}
			}
			$this->assign('opshow', $this->opshow());
		}
		$config = include_once FROOT.'/admin/common/config.php';
		parent::__construct($config);
	}
	function opshow(){
		$rules = $_SESSION['userinfo']['rules'];
		$oparr = 1;
		if(is_array($rules)){ //限制访问
			$oparr = array();
			foreach ($rules as $rule){
				if($rule['type'] == 1){ //增删改操作权限
					$oparr[$rule['rule']] = 1;
				}
			}
		}
		return $oparr;
	}
	function view($tpl='') {
		$ca = $this->show($tpl);
		$tpl = $ca['c'].'/'.$ca['a'];
		
		$this->load($tpl);
	
	}

	/**
	 *
	 * 页面展示
	 */
	function show($filename=''){
		parse_str($_SERVER['QUERY_STRING'],$query);
		$querys = explode('/', $query['act']);
		$ca = getCA($querys);
		if($filename){ //是否传值过来并调取相应模板页面
			$fs = explode('/',$filename);
			if(isset($fs[1])){
				if(empty($fs[0]) || empty($fs[1])){
					exit('404');
				}
				$ca['c'] = $fs[0];
				$ca['a'] = $fs[1];
			}else{
				$ca['a'] = $fs[0];
			}
		}
		return $ca;
	}
	
	/**
	 * 获取控制器和action
	 * @param unknown $querys
	 * @return multitype:Ambigous <string, unknown>
	 */
	function getCA($querys=array()){
		$white = array('login'); //白名单
		$c = $querys[0];
		$a = isset($querys[1])?$querys[1]:'';
		if(in_array($c,$white)){
			$a = $c;
			$c = 'auth';
		}
		if(!$a){
			$a = 'index';
		}
		return array('c'=>$c,'a'=>$a);
	}
}

?>

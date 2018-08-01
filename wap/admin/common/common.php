<?php
define('__PUBLIC__', '/admin/public/');
define('__APUBLIC__', '../view/public/');
function jsonwt($data=array()){
	exit(json_encode($data,JSON_UNESCAPED_UNICODE));
}
function tablename($table,$pre = 'wt_'){
	return $pre.$table;
}
function parentTo($url){
	exit("<script language='javascript'>top.location.href='$url'</script>");
}
function wturl($file){
	return $file.'.html';
}


/**
 * 获取控制器和action
 * @param unknown $querys
 * @return multitype:Ambigous <string, unknown>
 */
function getCA($querys=array()){
	$white = array('login'); //特殊请求名单
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

function isAjax(){
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		return true;
	}else{
		return false;
	}
}
/**
 * 是否是GET提交的
 */
function isGet(){
	return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
}
/**
 * 是否是POST提交
 * @return int
 */
function isPost() {
	return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
}
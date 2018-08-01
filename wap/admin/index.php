<?php
// header('Access-Control-Allow-Origin:*');
// error_reporting(0);
define('FROOT', str_replace("\\", '/', dirname(dirname(__FILE__))));
define('ETIME',24*60*60);
$query = $_GET;
$querys = explode('/', $query['act']);
if(!$querys){
	echo '404';die;
}
include_once 'common/common.php';
include_once '../common/pdo.php';
include_once 'common/page.php';

$ca = getCA($querys);
$c = $ca['c'];
$a = $ca['a'];
$file = FROOT."/admin/controller/$c.php";

if(!file_exists($file)){
	exit('file error');
}
include_once $file;
$classname = ucfirst($c).'Class';
if(!class_exists("$classname")){
	exit('c error');
	
}
if(!method_exists ($classname,$a)){
	exit('a error');
}
session_save_path(FROOT.'/data/session/');
@session_start();
$class = new $classname();
$class->$a();
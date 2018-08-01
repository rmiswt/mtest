<?php
/**
 * 
 * @author 王涛
 * 管理员权限
 *
 */
class AdminClass extends Page{
	public function edit(){
		if(isPost()){
			$data = $_POST;
			$id = intval($data['id']);
			if(!$id){
				$response = array('status'=>1,'msg'=>'参数错误');
				jsonwt($response);
			}
			unset($data['id']);
			if(isset($data['pwd']) && $data['pwd']){
				$data['pwd'] = md5('wt_'.$data['pwd']);
			}
			$count = PdoModel::update('account',$data,array('id'=>$id));
			if($count>0){
				$response = array('status'=>0,'msg'=>'修改成功');
			}else{
				$response = array('status'=>1,'msg'=>'修改失败');
			}
			jsonwt($response);
		}
		$id = intval($_GET['id']);
		if(!$id){
			$response = array('status'=>1,'msg'=>'参数错误');
			jsonwt($response);
		}
		$sql = 'select id,username,role_id from '.tablename('account').' where id=:id limit 1';
		$user = PdoModel::fetch($sql,array(':id'=>$id));
		$this->assign('user', $user);
		$sql = 'select * from '.tablename('role');
		$roles = PdoModel::fetchAll($sql);
		$this->assign('roles', $roles);
		$this->view();
	}
	public function adminlist(){
		$sql = 'select a.id,username,last_login_time,status,b.name rolename from '.tablename('account').' a,'.tablename('role').' b where a.role_id=b.id';
		$users = PdoModel::fetchAll($sql);
		foreach ($users as &$v){
			$v['last_login_time'] = date('Y-m-d H:i:s',$v['last_login_time']);
		}
		$this->assign('users', $users);
		$this->view('list');
	}
	public function add(){
		if(isPost()){
			$data = $_POST;
			$data['created_time'] = time();
			if(isset($data['pwd']) && $data['pwd']){
				$data['pwd'] = md5('wt_'.$data['pwd']);
			}
			$count = PdoModel::insert('account',$data);
			if($count>0){
				$response = array('status'=>0,'msg'=>'插入成功');
			}else{
				$response = array('status'=>1,'msg'=>'插入失败');
			}
			jsonwt($response);
		}
		$sql = 'select * from '.tablename('role');
		$roles = PdoModel::fetchAll($sql);
		$this->assign('roles', $roles);
		$this->view();
	}
	public function del(){
		$id = intval($_POST['id']);
		if(!$id){
			$response = array('status'=>1,'msg'=>'参数错误');
			jsonwt($response);
		}
		$count = PdoModel::delete('account',array('id'=>$id));
		if($count>0){
			$response = array('status'=>0,'msg'=>'删除成功');
		}else{
			$response = array('status'=>1,'msg'=>'删除失败');
		}
		jsonwt($response);
	}
	public function cate(){ //权限分类
		$sql = "select * from ".tablename('module');
		$modules = PdoModel::fetchAll($sql);
		$this->assign('modules', $modules);
		$this->view();
	}
	public function cateadd(){
		$data = $_POST;
		$count = PdoModel::insert('module',$data);
		if($count>0){
			$sql = "select * from ".tablename('module');
			$modules = PdoModel::fetchAll($sql);
			$_SESSION['modules'] = $modules;
			$response = array('status'=>0,'msg'=>'插入成功');
		}else{
			$response = array('status'=>1,'msg'=>'插入失败');
		}
		jsonwt($response);
	}
	public function catedel(){
		$id = intval($_POST['id']);
		if(!$id){
			$response = array('status'=>1,'msg'=>'参数错误');
			jsonwt($response);
		}
		$count = PdoModel::delete('module',array('id'=>$id));
		if($count>0){
			$sql = "select * from ".tablename('module');
			$modules = PdoModel::fetchAll($sql);
			$_SESSION['modules'] = $modules;
			$response = array('status'=>0,'msg'=>'删除成功');
		}else{
			$response = array('status'=>1,'msg'=>'删除失败');
		}
		jsonwt($response);
	}
	
	public function role(){
		if(!isset($_SESSION['rules'])){
			$sql = "select a.*,b.name modulename from ".tablename('rules').' a,'.tablename('module').' b where a.module_id=b.id';
			$rules = PdoModel::fetchAll($sql);
			$_SESSION['rules'] = $rules;
		}
		$rules = $_SESSION['rules'];
		$mru = array();
		foreach ($rules as $rule){
			$mru[$rule['id']] = $rule['name'];
		}
		$sql = "select id,name,rules from ".tablename('role');
		$roles = PdoModel::fetchAll($sql);
		foreach ($roles as &$role){
			$str = '';
			$rorules = explode(',', $role['rules']);
			foreach ($rorules as $v){
				if(isset($mru[$v])){
					$str .= $mru[$v].',';
				}
			}
			$role['rule'] = rtrim($str,',');
		}
		$this->assign('roles', $roles);
		$this->view();
	}
	public function roleadd(){
		if(isPost()){
			$data = $_POST;
			$ids = $data['rid'];
			$data['rules'] = implode(',', $ids);
			unset($data['rid']);
			$count = PdoModel::insert('role',$data);
			if($count>0){
				$response = array('status'=>0,'msg'=>'插入成功');
			}else{
				$response = array('status'=>1,'msg'=>'插入失败');
			}
			jsonwt($response);
		}
		$rules = $_SESSION['rules'];
		$ruless = array();
		foreach ($rules as $rule){
			$ruless[$rule['modulename']][] = $rule;
		}
		$this->assign('ruless', $ruless);
		$this->view();
	}
	public function roleedit(){
		if(isPost()){
			$data = $_POST;
			$id = intval($data['id']);
			if(!$id){
				$response = array('status'=>1,'msg'=>'参数错误');
				jsonwt($response);
			}
			$ids = $data['rid'];
			$data['rules'] = implode(',', $ids);
			unset($data['id'],$data['rid']);
			$count = PdoModel::update('role',$data,array('id'=>$id));
			if($count>0){
				$response = array('status'=>0,'msg'=>'更新成功');
			}else{
				$response = array('status'=>1,'msg'=>'更新失败');
			}
			jsonwt($response);
		}
		$id = intval($_GET['id']);
		if(!$id){
			exit('参数错误');
		}
		$sql = 'select * from '.tablename('role').' where id=:id limit 1';
		$role = PdoModel::fetch($sql,array(':id'=>$id));
		$role['rules'] = explode(',', $role['rules']);
		$this->assign('role', $role);
		
		$rules = $_SESSION['rules'];
		$ruless = array();
		foreach ($rules as $rule){
			$ruless[$rule['modulename']][] = $rule;
		}
		$this->assign('ruless', $ruless);
		$this->view();
	}
	public function roledel(){
		$id = intval($_POST['id']);
		if(!$id){
			$response = array('status'=>1,'msg'=>'参数错误');
			jsonwt($response);
		}
		$count = PdoModel::delete('role',array('id'=>$id));
		if($count>0){
			$response = array('status'=>0,'msg'=>'删除成功');
		}else{
			$response = array('status'=>1,'msg'=>'删除失败');
		}
		jsonwt($response);
	}
	public function rule(){
		if(isset($_SESSION['modules'])){
			$modules = $_SESSION['modules'];
		}else{
			$sql = "select * from ".tablename('module');
			$modules = PdoModel::fetchAll($sql);
			$_SESSION['modules'] = $modules;
		}
		$this->assign('modules', $modules);
		$rulesql  = 'select a.*,b.name modulename from '.tablename('rules'). ' a,'. tablename('module').' b where a.module_id=b.id';
		$rules = PdoModel::fetchAll($rulesql);
		$this->assign('rules', $rules);
		$this->view();
	}
	public function ruleadd(){
		$data = $_POST;
		$count = PdoModel::insert('rules',$data);
		if($count>0){
			$sql = "select a.*,b.name modulename from ".tablename('rules').' a,'.tablename('module').' b where a.module_id=b.id';
			$rules = PdoModel::fetchAll($sql);
			$_SESSION['rules'] = $rules;
			$response = array('status'=>0,'msg'=>'插入成功');
		}else{
			$response = array('status'=>1,'msg'=>'插入失败');
		}
		jsonwt($response);
	}
	public function ruledel(){
		$id = intval($_POST['id']);
		if(!$id){
			$response = array('status'=>1,'msg'=>'参数错误');
			jsonwt($response);
		}
		$count = PdoModel::delete('rules',array('id'=>$id));
		if($count>0){
			$sql = "select a.*,b.name modulename from ".tablename('rules').' a,'.tablename('module').' b where a.module_id=b.id';
			$rules = PdoModel::fetchAll($sql);
			$_SESSION['rules'] = $rules;
			$response = array('status'=>0,'msg'=>'删除成功');
		}else{
			$response = array('status'=>1,'msg'=>'删除失败');
		}
		jsonwt($response);
	}
	public function ruleedit(){
		if(isPost()){
			$data = $_POST;
			$id = intval($data['id']);
			if(!$id){
				$response = array('status'=>1,'msg'=>'参数错误');
				jsonwt($response);
			}
			unset($data['id']);
			$count = PdoModel::update('rules',$data,array('id'=>$id));
			if($count>0){
				$sql = "select a.*,b.name modulename from ".tablename('rules').' a,'.tablename('module').' b where a.module_id=b.id';
				$rules = PdoModel::fetchAll($sql);
				$_SESSION['rules'] = $rules;
				$response = array('status'=>0,'msg'=>'更新成功');
			}else{
				$response = array('status'=>1,'msg'=>'更新失败');
			}
			jsonwt($response);
		}
		$id = intval($_GET['id']);
		if(!$id){
			exit('参数错误');
		}
		$sql = 'select * from '.tablename('rules').' where id=:id limit 1';
		$rule = PdoModel::fetch($sql,array(':id'=>$id));
		$this->assign('rule', $rule);
		$modules = $_SESSION['modules'];
		$this->assign('modules', $modules);
		$this->view();
	}
}
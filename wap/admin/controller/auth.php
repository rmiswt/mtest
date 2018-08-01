<?php
/**
 * 
 * @author 王涛
 * 登录
 *
 */
class AuthClass extends Page{
	public function login(){
		if(isPost()){
			$username = $_POST['username'];
			$pwd = $_POST['password'];
			$sql = 'select id,pwd,isadmin from '.tablename('account').' where username=:username and status=1 limit 1';
			$user = PdoModel::fetch($sql,array(':username'=>$username));
			if(isset($user['pwd'])){
				if($user['pwd'] == md5('wt_'.$pwd)){
					$_SESSION['userinfo'] = array('uid'=>$user['id'],'username'=>$username,'expiretime'=>time()+ETIME,'rules'=>0,'isadmin'=>$user['isadmin']);
					$rolesql = 'select a.rules from '.tablename('role').' a,'.tablename('account')." b where a.id=b.role_id and b.id='{$user['id']}'";
					$role = PdoModel::fetch($rolesql);
					$rulesql  = 'select a.*,b.name modulename from '.tablename('rules') .' a,'.tablename('module') .' b where a.module_id=b.id ';
					if(isset($role['rules']) && $role['rules'] != '0'){ //限制权限
						$rulesql  .= "  and a.id in ({$role['rules']})";
					}
					$rulesql  .= " order by a.display_order desc";
					$rules = PdoModel::fetchAll($rulesql);
					$_SESSION['userinfo']['rules'] = $rules;
					PdoModel::update('account',array('last_login_time'=>time()),array('id'=>$user['id']));
					$response = array('status'=>0,'msg'=>'登录成功');
				}else{
					$response = array('status'=>1,'msg'=>'密码错误');
				}
			}else{
				$response = array('status'=>1,'msg'=>'账号不存在或未启用');
			}
			jsonwt($response);
		}
		if(isset($_SESSION['userinfo']) && $_SESSION['userinfo']){
			header('Location:/admin/index/index.html');
			exit;
		}
		$this->view();
	}
	public function logout(){
		unset($_SESSION['userinfo']);
		header('Location:/admin/login.html');
		exit;
	}
	public function clear(){
		unset($_SESSION['module'],$_SESSION['rules']);
		exit;
	}
}
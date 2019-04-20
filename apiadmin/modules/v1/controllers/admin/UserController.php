<?php
namespace apiadmin\modules\v1\controllers\admin; 
use apiadmin\modules\v1\controllers\CoreController;
use Yii;
use apiadmin\modules\models\admin\User;
use apiadmin\modules\models\admin\Roles;
/*
	用户相关控制器
*/

class UserController extends CoreController
{
	//角色列表
	public function actionList()
	{
		$where = ['creater_id'=>$this->_uid];
		$list = User::getUserList($where);
		$roles = Roles::getRoles($this->_user);
		$roleList = array();
		foreach($roles as $val){
			$roleList[$val['id']] = $val['role_name'];
		}
		$this->out('用户列表',$list,array('role_list'=>$roleList));
	}

	/*用户添加 修改*/
	public function actionUser_edit()
	{
		$user = new User;
		$params = $this->request;
		if($id=$this->request('id')){
			$user = $user::findOne($id);
			$params['update_time'] =  time();
			$user->scenario = 'Edit';
		}else{
			$params['pid'] = $this->_uid;
			$params['creater_id']  = $this->_uid;
			$params['create_time']  = time();
			$user->scenario = 'Reg';
		}	

		if($this->request('password'))
			$params['password'] = md5($this->request('password'));
		else
			unset($params['password']);


		if($user->load($params,''))
		{	
			if($user->validate() == true && $user->save()){
				if(!$params['id']) $user->id = Yii::$app->db->getLastInsertID();
				$this->out('操作成功',$user->attributes);
			}
			$error = $user->getErrors();
			$errMsg = $this->model_errors($error);
			$this->error($errMsg);

		}else{
			$error = $user->getErrors();
			$errMsg = $this->model_errors($errMsg);
			$this->error($errMsg);
		}		
	}

	//删除
	public function actionUser_del()
	{
		if(!$id = $this->request('id'))
			$this->error('参数错误');

		$where = ['id'=>$id];
		$res = User::deleteAll($where);
		if($res) $this->out('删除成功');
		$this->error('删除失败');

	}


	//获取用户信息
	public function actionUser_info()
	{
		$data = [
			'user_name' => $this->_user['user_name'],
			'lastLogin' => $this->_user['last_login_time'],
			'id'		=> $this->_uid,
			'avatar'	=> $this->_user['avatar'],
		];
		$this->out('用户信息',$data);
	}

	//信息修改
	// old_pass
	// new_pass
	// user_name
	public function actionEdit_info()
	{
		
		if(!$id = $this->request('id')) $this->error('参数错误');

		$user = new User;
		$user = $user::findOne($id);

		//用户名修改
		if($this->request('user_name')){
			$user->user_name = $this->request('user_name');
		}

		//密码修改
		if($this->request('old_pass') && $this->request('new_pass'))
		{
			if(md5($this->request('old_pass'))!=$this->_user['password'])
				$this->error('密码错误');		
	
			$user->password  = md5($this->request('new_pass'));			
		}

		//头像修改
		if($this->request('avatar')){
			$user->avatar = $this->request('avatar');
		}

		$user->update_time = time();
		if($user->save(false)) $this->out('修改成功');
		$this->error('修改失败');
	}



}
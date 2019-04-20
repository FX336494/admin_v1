<?php
namespace apiadmin\modules\v1\controllers\admin; 
use apiadmin\modules\v1\controllers\CoreController;
use Yii;
use apiadmin\modules\models\admin\Roles;

/*
	用户角色相关控制器
*/

class RoleController extends CoreController
{
	//角色列表
	public function actionRole_list()
	{
		$roleList = Roles::getRoles($this->_user);
		$this->out('角色列表',$roleList);
	}

	/*角色添加 修改*/
	public function actionRole_add()
	{
		$role = new Roles;
		$params = $this->request;
		if($id=$this->request('id')){
			$role = $role::findOne($id);
		}	

		if($role->load($params,''))
		{	
			$role->create_time = time();
			$role->uid = $this->_uid;
			if($role->validate() == true && $role->save()){
				if(!$params['id']) $role->id = Yii::$app->db->getLastInsertID();
				$this->out('操作成功',$role->attributes);
			}
			$error = $role->getErrors();
			$errMsg = $this->model_errors($error);
			$this->error($errMsg);

		}else{
			$error = $role->getErrors();
			$errMsg = $this->model_errors($errMsg);
			$this->error($errMsg);
		}		
	}

	//删除
	public function actionRole_del()
	{
		if(!$id = $this->request('id'))
			$this->error('参数错误');

		$where = ['id'=>$id];
		$res = Roles::deleteAll($where);
		if($res) $this->out('删除成功');
		$this->error('删除失败');

	}


	//获取角色对应的权限菜单
	//id 角色ID
	public function actionRole_auth_list()
	{
		$roleId   = $this->request('id');
		$authData = Roles::showRoleAuth($roleId);
		$this->out('角色权限列表',$authData);
	}

	/*
		修改角色权限
		auth string 角色的权限集 形如 3,23,24
 	*/
	public function actionRole_auth_edit()
	{
		$authIds = $this->request('auths');
		if(!$roleId = $this->request('id')) $this->error('参数错误');

		$flag = Roles::editRoleAuth($roleId,['rule_ids'=>$authIds]);
		if($flag) $this->out('操作成功');
		$this->error('操作失败');
	}

}
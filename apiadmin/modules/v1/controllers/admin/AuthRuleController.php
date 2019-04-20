<?php
namespace apiadmin\modules\v1\controllers\admin; 
use apiadmin\modules\v1\controllers\CoreController;
use Yii;
use apiadmin\modules\models\admin\AuthRule;
use apiadmin\modules\models\admin\Roles;
/*
	用户权限相关控制器
*/

class AuthRuleController extends CoreController
{

	/*
		获取菜单列表
		pid 父id
	*/
	public function actionAuth_rule_list()
	{
		$pid = $this->request('pid');
		$data = AuthRule::getChildMenu();
		$this->out('权限列表'.$pid,$data);
	}


	//添加菜单
	public function actionAdd_auth_rule()
	{
		$authRule = new AuthRule;
		$params = $this->request;
		if($params['id']){
			$authRule = $authRule::findOne($params['id']);
		}
		
		if($authRule->load($params,''))
		{	
			$authRule->create_time = time();
			if($authRule->validate() == true && $authRule->save()){
				if(!$params['id']) $authRule->id = Yii::$app->db->getLastInsertID();
				$this->out('操作成功',$authRule->attributes);
			}
			$error = $authRule->getErrors();
			$errMsg = $this->model_errors($error);
			$this->error($errMsg);

		}else{
			$error = $authRule->getErrors();
			$errMsg = $this->model_errors($errMsg);
			$this->error($errMsg);
		}
	}


	//删除菜单
	//id
	public function actionDel_auth_rule()
	{
		if(!$id = $this->request('id')) $this->error('参数错误');

		$delMenu = AuthRule::getChildMenu($id);
		$ids = [$id];
		if($delMenu)
		{
			foreach($delMenu as $menu)
			{
				$ids[] = $menu;
			}			
		}
		$where = ['in','id',$ids];
		$res = AuthRule::deleteAll($where);

		if($res) $this->out('删除成功');
		$this->error('删除失败');

	}


}
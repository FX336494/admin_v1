<?php
namespace apiadmin\modules\v1\controllers\admin; 
use apiadmin\modules\v1\controllers\CoreController;
use Yii;
use apiadmin\modules\models\admin\User;

class ConnectController extends CoreController
{
	public function actionTest()
	{
		echo  Yii::$app->security->generateRandomString();
		$where = ['id'=>'1'];
		 User::getUser($where);
	}

	/*
		登录
		user_name 用户名
		password  密码
	*/
	public function actionLogin()
	{
		$userName = $this->request('username');
		$pass  = $this->request('password');
		$where = ['user_name'=>$userName];
		$user  = User::getUser($where);
		if(!$user) $this->error('用户不存在');
		if($user['password']!=md5($pass)) $this->error('密码错误');

		//登录成功 成功auth_key
		$authKey = User::generateAuthKey();
		$data  = array('auth_key'=>$authKey);
		$data['user_name'] = $user['user_name'];
		$data['login_time'] = time();
		$data['last_login_time'] = $user['login_time']?$user['login_time']:time();

		if(!User::updateUserById($data,$user['id'])) $this->error('登录失败');

		//获取用户权限
		$rulesTree = User::getRulesTree($user);
		$allRules = User::getRules($user);
		$data['avatar'] = $user['avatar'];
		$extend = array('rules_tree'=>$rulesTree,'rules'=>$allRules);

		$this->out('登录成功',$data,$extend);

	}
}

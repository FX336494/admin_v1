<?php
/*
	平台后台管理员角色相关模型
*/
namespace apiadmin\modules\models\admin;
use Yii;
use apiadmin\modules\models\admin\AuthRule;
use apiadmin\modules\models\admin\User;

class Roles extends \yii\db\ActiveRecord
{

	public static function tableName()
	{
		return "{{%fx_role}}";
	}

	public function rules()
	{
		return [
			[['role_name'],'required'],
			[['role_name','role_desc'],'string'],
		];		
	}

	//获取角色列表
	public static function getRoles($user)
	{
		// if($user['role_id']=='1')
		// 	$where = ['>','id','0'];
		// else
		$where = ['uid'=>$user['id']];
		$list = self::find()->where($where)->asarray()->all();
		return $list;
	}


	/*
		角色权限
	*/
	public static function getRolesRules($roleId,$isTree=false)
	{
		$role = self::findOne(['id'=>$roleId]);
		if($roleId=='1'){
			//这里定死角色id为1的 是超级管理员
			$where = ['>','id','0'];
		}else{
			$ruleIds = explode(',', $role['rule_ids']);
			$where = ['in','id',$ruleIds];			
		}

		$data = AuthRule::getAuthRules($where);	
		if($isTree) $data = self::getTree($data); 
		return $data;	
	}

	/*
		角色权限  树状结构的
	*/
	public static function getRolesRulesTree($roleId)
	{

		$role = self::findOne(['id'=>$roleId]);
		if($roleId=='1'){
			//这里定死角色id为1的 是超级管理员
			$where = ['and',"is_menu='1'",['>','id','0']];
		}else{
			$ruleIds = explode(',', $role['rule_ids']);
			$where = ['and',"is_menu='1'",['in','id',$ruleIds]];  
		}

		$data = AuthRule::getAuthRules($where);
		$rules = self::getTree($data);
		return $rules;

	}

	public static function getTree($items,$pid='pid')
	{

    	$map  = [];
    	$tree = [];   
    	foreach ($items as &$it)
    	{ 
    		//数据的ID名生成新的引用索引树
    		$map[$it['id']] = &$it; 
    	}  
    	foreach ($items as &$it)
    	{
	        $parent = &$map[$it[$pid]];

	        //选中的子菜单，但没有父级菜单
	        if($it[$pid]>0 && !$parent){
	        	$parentMenu = self::getParent($it[$pid]);
	        	$map[$parentMenu['id']] = $parentMenu;
	        	$tree[] = &$map[$it[$pid]];//$parentMenu;
	        	$parent = &$map[$it[$pid]];
	        }

	        if($parent) {
	            $parent['children'][] = &$it;
	        }else{
	            $tree[] = &$it;
	        }
    	}	
    	return $tree;
	}

	public static function getParent($id)
	{
		return AuthRule::getAuthRule($id);
	}


	//显示角色权限, 并且将其可以添加的权限都显示出来,即此角色上级的所有权限 
	public static function showRoleAuth($roleId)
	{
		//当前角色权限
		$role = self::findOne(['id'=>$roleId]);
		$curRules = explode(',',$role['rule_ids']);

		//此角色的创建人
		$createUser = User::getUser(['id'=>$role['uid']]);
		$createRole = self::findOne(['id'=>$createUser['role_id']]);

		//获取创建人所拥有的权限
		$authRuleTree = self::getRolesRules($createRole['id'],true);	

		//返回所有可显示的权限  和 当前角色所拥有的权限
		return array('auth_tree'=>$authRuleTree,'role_auth'=>$curRules);
	}
	

	//修改角色权限
	public static function editRoleAuth($id,$data)
	{
		return self::updateAll($data,['id'=>$id]);
	}

}
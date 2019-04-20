<?php
/*
	平台后台管理员 角色权限 相关模型
*/
namespace apiadmin\modules\models\admin;
use Yii;

class AuthRule extends \yii\db\ActiveRecord
{

	public static function tableName()
	{
		return "{{%fx_auth_rule}}";
	}

	public function rules()
	{
		return [
			[['name','pid'],'required'],
			[['name','path','is_menu','desc','icon'],'string'],
			[['pid','sort'],'integer'],
		];
	}

	//获取单个权限
	public static function getAuthRule($id)
	{
		$data = self::find()->where(['id'=>$id])->asArray()->one();
		return $data;		
	}

	//获取权限
	public static function getAuthRules($where,$order='sort ASC')
	{
		$data = self::find()->where($where)->orderBy($order)->asArray()->all();
		return $data;
	}


	//获取下面所有子菜单
	public static function getChildMenu($pid='0')
	{
		$where = ['>','id','0'];
		$data = self::getAuthRules($where);
		$childMenu = self::getSubMenu($data,$pid);
		return $childMenu;
	} 


	public static function getSubMenu($menus,$pid=0,&$list=array(),$level=0)
	{
		foreach($menus as $menu)
		{
			if($menu['pid']==$pid){
				$menu['level'] = $level;
				$list[] = $menu;
				self::getSubMenu($menus,$menu['id'],$list,$level+1);
			}
		}
		return $list;
	}




}
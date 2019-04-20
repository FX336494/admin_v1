<?php
/*
	平台后台管理员相关模型
*/
namespace apiadmin\modules\models\admin;
use Yii;
use apiadmin\modules\models\admin\Roles;

class User extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fx_user}}';
    }

    public function rules()
    {
        return [
            [['user_name','role_id'],'required','on'=>'Reg'],
            [['user_name'],'required','on'=>'Edit'],
            [['user_name','password'],'string'],
            [['role_id','pid','creater_id','create_time','update_time'],'integer'],
        ];      
    }    

    //获取用户信息
    public static function getUser($where,$field=['*'])
    {
    	return self::find()->where($where)->asarray()->one();
    }

    //获取用户列表
    public static function getUserList($where)
    {
        return self::find()->where($where)->asarray()->all();
    }

    /*
      * 通过authkey登录
    */
    public static function loginByAuthkey($authKey)
    {
        return self::findOne(['auth_key' => $authKey]);
    }

    //生成auth_key
    public static function generateAuthKey()
    {
        return Yii::$app->security->generateRandomString();
    }


    //通过更新
    public static function updateUserById($data,$id)
    {
    	return self::updateAll($data,['id'=>$id]);
    }


    //获取用户的权限 菜单 按层分好了
    public static function getRulesTree($user)
    {
        //返回所有菜单
        $data = Roles::getRolesRulesTree($user['role_id']);
        return $data;
    		
    }

    public static function getRules($user)
    {
        //返回所有菜单
        $data = Roles::getRolesRules($user['role_id']);
        return $data;        
    }



}
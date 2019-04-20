<?php

namespace apiadmin\modules\models\member;
use Yii;
use yii\db\ActiveRecord;
class Member extends ActiveRecord
{


	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }


    
}
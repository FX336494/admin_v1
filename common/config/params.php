<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    //七牛云图片上传配置
    'qiniu' => array(
        'open'  => false,
    	'ak'	=> '',
    	'sk'    => '',
    	'bucket'=> '',
    	'zone'  => '',  //所属区
    	'domain'=> '',  //图片域名
    ),       
];

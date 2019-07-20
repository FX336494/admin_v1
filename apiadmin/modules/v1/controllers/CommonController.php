<?php

/*
	存放一些公共调用、和不需要登录验证的接口
*/

namespace apiadmin\modules\v1\controllers;

use Yii;
use yii\db\Query;
use common\utils\FileUpload;
use crazyfd\qiniu\Qiniu;

class CommonController extends CoreController
{
	//图片上传
	public function actionUpload() 
	{
		$qiNiuOpen 	= Yii::$app->params['qiniu']['open'];
		if($qiNiuOpen)
			$this->qinuiUpload();
		else
			$this->fileUpload();
	}

	//本地上传
	private function fileUpload()
	{
		$basePath = $_SERVER['DOCUMENT_ROOT'];
		$filePath = $this->getFilePath($this->request('uptype'));		
		$upload = new FileUpload();
		$res = $upload->upload('file',$basePath.'/data/'.$filePath); 
		if($res){
			$fileName = $upload->getFileName();
			$data = array('url'=>'http://'.$_SERVER['HTTP_HOST'].$filePath.'/'.$fileName);
			$this->out('上传成功',$data);
		}
		$this->error('上传失败'.$upload->getErrorMsg());		
	}

	//七牛上传
	private function qinuiUpload()
	{
		$qiNiuConf 	= Yii::$app->params['qiniu'];
		$ak 		= $qiNiuConf['ak'];
		$sk 		= $qiNiuConf['sk'];
		$domain 	= $qiNiuConf['domain'];
		$bucket 	= $qiNiuConf['bucket'];
		$zone 		= $qiNiuConf['zone'];	
		$qiniu 		= new Qiniu($ak, $sk,$domain, $bucket,$zone);
		$key 		= time();
		$key 		.= strtolower(strrchr($_FILES['file']['name'], '.'));
		$filePath 	= $this->getFilePath($this->request('uptype'));
		$key 		= $filePath.$key;

		$res = $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
		$data = array('url'=>'http://'.$domain.'/'.$key); 
		$this->out('上传结果:'.json_encode($res),$data);
	}

	//图片存放路径
	private function getFilePath($upType)
	{
		switch ($upType) {
			case '1':
				$filePath = 'upload/avatar';	
				break;
			
			default:
				$filePath = 'upload/avatar';	
				break;
		}
		return $filePath;
			
	}



}


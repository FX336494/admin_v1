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
	private $uploadBasePath  = '';

	//图片上传 本地上传
	// FILES
	// uptype 上传类型 如 1头像上传
	public function actionUpload() 
	{
		// $this->write_log($this->request);
		$basePath = $_SERVER['DOCUMENT_ROOT'];
		$filePath = $this->getFilePath($this->request('uptype'));
		
		$upload = new FileUpload();
		$res = $upload->upload('file',$basePath.$filePath);
		if($res){
			$fileName = $upload->getFileName();
			$data = array('url'=>'http://'.$_SERVER['HTTP_HOST'].$filePath.'/'.$fileName);
			$this->out('上传成功',$data);
		}
		$this->error('上传失败');
	}

	//图片存放路径
	private function getFilePath($upType)
	{
		switch ($upType) {
			case '1':
				$filePath = '/data/upload/avatar';	
				break;
			
			default:
				$filePath = '/data/upload/avatar';	
				break;
		}
		return $filePath;
			
	}
	
	
	//上传到七牛
	public function actionQiniu()
	{
		//填上你自己注册的七牛的信息
		$ak = '';
		$sk = '';
		$domain = ''; 
		$bucket = '';
		$zone = '';	
		$host = '';
		$qiniu = new Qiniu($ak, $sk,$domain, $bucket,$zone);
		$key = time();
		$key .= strtolower(strrchr($_FILES['file']['name'], '.'));
		$filePath = $this->getQiniuImageKey($this->request('uptype'));
		$key = $filePath.$key;

		$res = $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
		$data = array('url'=>$host.'/'.$key); 
		$this->out('上传结果:'.json_encode($res),$data);
	}

	private function getQiniuImageKey($upType)
	{
		switch ($upType) {
			case '1':
				$filePath = 'avatar/';	
				break;
			
			default:
				$filePath = 'avatar/';	
				break;
		}
		return $filePath;		
	}




}


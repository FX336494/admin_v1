<?php
namespace apiadmin\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use common\models\DB;
use apiadmin\modules\models\admin\User;
use common\utils\Model;
 

/**
 * 接口基础处理
 * @author Administrator
 * @param  所有版本接口的控制器父类
 */
class CoreController extends ActiveController
{
    public  $modelClass = 'apiadmin\modules\models\admin\User'; 
    private $http_id;
    public  $post;
    public  $get;
    public  $_user;  //用户信息
    public  $_uid;
    public  $request;
    public  $db;

    
    public function beforeAction($action)
    {
        $this->request = array_merge(Yii::$app->request->post(),Yii::$app->request->get(),$_FILES); 

        $this->http_id = DB::insert('http_log', [
            'method' => $_SERVER['REQUEST_METHOD'],
            'full_url' => Yii::$app->request->absoluteUrl,
            'http_status' => Yii::$app->response->statusCode,
            'ips' => Yii::$app->request->userIP,
            'request' => json_encode($this->request, JSON_UNESCAPED_UNICODE),
            'create_at' => date("Y-m-d H:i:s")
        ]);

        $mod = ['common'];
        $actionMod = ['reg','login','test'];
        $controller = Yii::$app->controller->id;
        $actionName = Yii::$app->controller->action->id;
        if(in_array($controller, $mod) || in_array($actionName, $actionMod)) return true;
            
       if(!isset($this->request['auth_key'])) $this->error('auth_key is null.');

        $this->_user = User::loginByAuthkey($this->request['auth_key']);        

        if(empty($this->_user)) $this->error('用户不存在','200','404');

        $this->_user = $this->_user->toarray();

        $this->_uid = $this->_user['id']; 
        $this->db = new DB();
        return true;
    }

    public function request($key,$default='')
    {
        $request = array_merge(Yii::$app->request->post(),Yii::$app->request->get(),$_FILES);
        return isset($request[$key])?$request[$key]:$default;
    }
     
        
    public function out($msg='', $res = [],$extend=[]){
        $data = [
            'status' => "200"  ,
            'code'   => '0',
            'msg'    => (string)$msg,
            'data'   => $res, 
        ];
        if($extend) $data['extend'] = $extend; 
       return $this->send($data);  
    }
        
    public function error($msg='', $status = '200',$code='1'){  
        $data = [
            'status'=> $status."",
            'code'  => $code,
            'msg'   => (string)$msg,
        ];
        return  $this->send($data);
    }

    public function send($data=[])
    {
        $out = json_encode($data,JSON_UNESCAPED_UNICODE);
        DB::update('http_log', [ 'http_status' => $data['status'], 'response' => $out,'finish_at'=>date("Y-m-d H:i:s") ], ['id'=>$this->http_id]);
        exit($out);
    }


    protected function parameter($request = [], $model = [])
    {
        $request['create_time'] = time(); // '创建时间',  
        foreach ($request as $k => $v) {
            if (array_key_exists($k, $model->attributes))
                $model->$k = $v;
        }
        
        return $model;
    }
    
    
    public function model($model,$params,$scenario='')
    {

        $classDir  = 'apiadmin\\modules\\models'.'\\'.$model;   
        $m = new $classDir();      
        if(!$m){
            $classDir = '\\common\\models\\'.$model;
            $m = new $classDir();  
        } 

        if(!$m) $this->error(' model cannot be blank.');
        if($scenario) $m->scenario = $scenario;

        $m = $this->parameter($params,$m);
        $m->validate() ?:$this->error($this->model_errors($m->errors));
        return $m;
    }
    
    public function model_errors($errors=[]){
        foreach ($errors as $k=>$v){
            return $v[0];
        }
        return 'model_errors';
    }


    public function get_curl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
    
    
    public function post_curl($url,$params)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        // curl_setopt( $ch, CURLOPT_USERAGENT , 'xinykj.com' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch , CURLOPT_POST , 1 );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }

    //记入日志
    public function write_log($content,$tempDir='')
    {
        if(is_array($content) || is_object($content))
            $content = json_encode($content,JSON_UNESCAPED_UNICODE);
        
        $content = "[".date('Y-m-d H:i:s')."]".$content."\r\n";
        $dir = rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/').'/logs';
        if($tempDir) $dir .= '/'.$tempDir;
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $path = $dir.'/'.date('Y-m-d').'.txt';
        file_put_contents($path,$content,FILE_APPEND);
    }   

    
}

?>
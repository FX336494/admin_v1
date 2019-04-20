<?php
namespace common\models;

use Yii;


class DB 
{
    
	public static function query($sql,$a=''){
	    $reslt = \Yii::$app->db->createCommand($sql)->execute();
		return $reslt;
	}


	// 返回一行 (第一行) 如果该查询没有结果则返回 false
	public static function get_one($sql, $type = ''){
	    $r = \Yii::$app->db->createCommand($sql)->queryOne();
		if($type) return (object)$r;
		return $r;
	}
	 
	// 返回一列 (第一列) 如果该查询没有结果则返回空数组
	public static function get_column($sql){
	    $r = \Yii::$app->db->createCommand($sql)->queryColumn(); 

	    return $r;
	}
	
	// 返回一个标量值 如果该查询没有结果则返回 false
	public static function get_scalar($sql){
	    $r = \Yii::$app->db->createCommand($sql)->queryScalar();
	    return $r;
	}

	// 返回多行. 每行都是列名和值的关联数组. 如果该查询没有结果则返回空数组
	public static function get_all($sql){
	    $data = \Yii::$app->db->createCommand($sql)->queryAll();
		return $data;
	}
	
	public static function limit($page=1,$total=10){
	    return "limit " . (Yii::$app->request->post('page',$page) - 1) * Yii::$app->request->post('total',$total) . ", " . Yii::$app->request->post('total',$total);
	}

	public static function insertid(){
	    return \Yii::$app->db->getLastInsertID();
	}

	public static function nums($sql){
	    return count(\Yii::$app->db->createCommand($sql)->queryAll());
	}
	
	public static function dowith($str='')
	{
	    $str = str_replace("'",".",$str);
	    //$str = str_replace('"','',$str);
	    return $str;
	}
	        
	static function implode_field_value($array, $glue = ',') {
		$sql = $comma = '';
		foreach ($array as $k => $v) {
			$sql .= $comma."`$k`= '".DB::dowith($v)."'";
			$comma = $glue;
		}
		return $sql;
	}

	public static function insert($table, $data=[], $return_insert_id = false, $replace = false, $silent = false) {
		$sql = DB::implode_field_value($data);
		$cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';
		$silent = $silent ? 'SILENT' : '';
		DB::query("$cmd $table SET $sql", $silent);
		$insertid = DB::insertid();
		return $insertid;
	}

	public static function batchInsert($table, $data ) {
	    foreach ($data as $val){
	        DB::insert($table, $val);
	    }
	    return true;
	}
	

	    
	public static function update($table, $data, $condition, $unbuffered = false, $low_priority = false) {
		$sql = DB::implode_field_value($data);
		$cmd = "UPDATE ".($low_priority ? 'LOW_PRIORITY' : '');
		$where = '';
		if(empty($condition)) {
			$where = '1';
		} elseif(is_array($condition)) {
			$where = DB::implode_field_value($condition, ' AND ');
		} else {
			$where = $condition;
		}
		$res = DB::query("$cmd $table SET $sql WHERE $where");
		return $res;
	}
	
}
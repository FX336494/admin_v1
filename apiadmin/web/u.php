<?php
/* http://www.upupw.net */
/* webmaster@upupw.net */
$version="15.12.5AQ";
date_default_timezone_set('Asia/Shanghai') && error_reporting(0);
function _GET($n) { return isset($_GET[$n]) ? $_GET[$n] : NULL; }
function _SERVER($n) { return isset($_SERVER[$n]) ? $_SERVER[$n] : '[undefine]'; }
function memory_usage() { $memory  = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB'; return $memory;}
function micro_time_float() { $mtime = microtime(); $mtime = explode(' ', $mtime); return $mtime[1] + $mtime[0];}
function get_hash() {
  return sha1(uniqid());
}
@session_start();
$currentTime = time();
$changeTime = 86400;
$rand = '';
if(isset($_SESSION['time']) and ($currentTime - $_SESSION['time']) < $changeTime) {
  $rand = $_SESSION['rand'];
}else{
  $_SESSION['time'] = $currentTime;
  $_SESSION['rand'] = $rand = get_hash();
}
@header("content-Type: text/html; charset=utf-8");
define('YES', '<span style="color: #008000; font-weight : bold;">已开启</span>');
define('NO', '<span style="color: #e74c3c; font-weight : bold;">未开启</span>');
$Info = array();
$Info['php_ini_file'] = function_exists('php_ini_loaded_file') ? php_ini_loaded_file() : '[undefine]';
$link = @mysqli_connect("127.0.0.1", $_POST['mysqlUser'], $_POST['mysqlPassword']);
$errno = mysqli_connect_errno();
$pinfo = $rand;
$up_start = micro_time_float();
if (_GET($pinfo) == 'phpinfo') {
if (function_exists('phpinfo')) phpinfo();
else echo "PHPINFO函数已被禁用无法正常显示详细信息!";
exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UPUPW PHP探针安全版-APACHE环境包PHP7专版</title>
<meta name="keywords" content="php探针,upupw探针,PHP探针安全版,php7探针,phpinfo,php扩展,数据库连接测试" />
<meta name="description" content="upupw apache环境包php7专版探针,需成功测试连接本地数据库后才显示phpinfo动态链接页面和网站路径,防止路径信息泄露." />
<meta name="author" content="UPUPW" />
<meta name="reply-to" content="webmaster@upupw.net" />
<meta name="copyright" content="UPUPW Team" />
<style type="text/css">
<!--
*{margin:0px;padding:0px;}
body {background-color:#FFFFFF;color:#000000;margin:0px;font-family:"\5fae\8f6f\96c5\9ed1",tahoma,arial,sans-serif;}
input {text-align:center;width:200px;height:20px;padding:5px;}
a:link {color:green; text-decoration:none;}
a:visited {color:green;text-decoration:none;}
a:active {color:green;text-decoration:none;}
a:hover {color:#ed776b;text-decoration:none;}
table {border-collapse:collapse;margin:10px 0px;clear:both;}
.inp tr th, td {padding:2px 5px 2px 5px;vertical-align:center;text-align:center;height:30px; border:1px #FFFFFF solid;}
.head1 { background-color: #2c3e50; width: 100%; font-size: 36px; color: #ffffff; padding-top: 10px; text-align: center; font-family: Georgia, "Times New Roman", Times, serif; font-weight: bold; }
.head2 { background-color: #1abc9c; width: 100%; font-size: 18px; height: 18px; color: #ffffff; }
.el { text-align: center; background-color: #d3e1e5; }
.er { text-align: right; background-color: #d3e1e5; }
.ec { text-align: center; background-color: #1abc9c; font-weight: bold; color: #FFFFFF; }
.fl { text-align: left; background-color: #ecf0f1; color: #505050; }
.fr {text-align:right;background-color:#eeeeee;color:#505050;}
.fc { text-align: center; background-color: #ecf0f1; color: #505050; }
.ft {text-align:center;background-color: #D9F9DE;color:#060;}
a.arrow {font-family:webdings,sans-serif;font-size:10px;}
a.arrow:hover {color:#ff0000;text-decoration:none;}
-->
</style>
</head>
<body>
<div class="head1"><a href="http://www.upupw.net/tanzhen/n126.html" style="color:#ffffff" >{ UPUPW PHP 探针 }</a></div>
<div class="head2"></div>
<div style="margin:0 auto;width:1001px;overflow:hidden;">
<table width="100%" class="inp">
<tr>
<th colspan="2" class="ec" width="50%">服务器信息</th>
<th colspan="2" class="ec" width="50%">PHP功能组件开启状态</th>
</tr>
<tr>
<td class="er" width="12%">服务器域名</td>
<td class="fl" width="38%"><?=_SERVER('SERVER_NAME')?></td>
<td class="er" width="20%">MySQLi Client组件</td>
<td class="fc" width="30%"><?=get_extension_funcs('mysqli') ? YES : NO ?></td>
</tr>
<tr>
<td class="er">服务器端口</td>
<td class="fl"><?=_SERVER('SERVER_ADDR').':'._SERVER('SERVER_PORT')?></td>
<td class="er">cURL组件</td>
<td class="fc"><?=get_extension_funcs('curl') ? YES : NO ?></td>   
</tr>
<tr>
<td class="er">服务器环境</td>
<td class="fl"><?=stripos(_SERVER('SERVER_SOFTWARE'), 'PHP')?_SERVER('SERVER_SOFTWARE'):_SERVER('SERVER_SOFTWARE')?></td>
<td class="er">GD library组件</td>
<td class="fc"><?=get_extension_funcs('gd') ? YES : NO ?></td>    
</tr>
<tr>
<td class="er">PHP运行环境</td>
<td class="fl"><?=PHP_SAPI .' PHP/'.PHP_VERSION?></td>
<td class="er">EXIF信息查看组件</td>
<td class="fc"><?=get_extension_funcs('exif') ? YES : NO ?></td>   
</tr>
<tr>
<td class="er">PHP配置文件</td>
<td class="fl"><?php if(isset($_POST['act'])) {?>
<?=(@mysqli_select_db($link, $_POST['mysqlDb']))?htmlentities($Info['php_ini_file']):"<span style='color:red'>数据库连接测试错误拒绝显示</span>"?>
<?php }
else echo "<span style='color:green'>数据库连接测试成功后显示</span>";
?>
</td>
<td class="er">OpenSSL协议组件</td>
<td class="fc"><?=get_extension_funcs('openssl') ? YES : NO ?></td>   
</tr>
<tr>
<td class="er">当前网站目录</td>
<td class="fl"><?php if(isset($_POST['act'])) {?>
<?=(@mysqli_select_db($link, $_POST['mysqlDb']))?htmlentities(_SERVER('DOCUMENT_ROOT')):"<span style='color:red'>数据库连接测试错误拒绝显示</span>"?>
<?php }
else echo "<span style='color:green'>数据库连接测试成功后显示</span>";
?>
</td>
<td class="er">Mcrypt加密处理组件</td>
<td class="fc"><?=get_extension_funcs('mcrypt') ? YES : NO ?></td>
</tr>
<tr>
<td class="er">服务器标准时</td>
<td class="fl">
<?=gmdate('Y-m-d H:i:s', time() + 3600 * 8)?>
</td>
<td class="er" >IMAP电子邮件函数库</td>
<td class="fc"><?=get_extension_funcs('imap') ? YES : NO ?></td>
</tr>
<tr>
<td class="er">便捷管理入口</td>
<td class="fl"><?php if(isset($_POST['act'])) {?>
<?=(@mysqli_select_db($link, $_POST['mysqlDb']))?"<a target='_blank' href='?$pinfo=phpinfo'>PHPINFO详细信息</a> | <a target='_blank' href='/pmd/'>phpMyAdmin管理</a>":"<span style='color:red'>数据库连接测试错误拒绝显示</span>"?>
<?php }
else echo "<span style='color:green'>数据库连接测试成功后显示</span>";
?>
</td>
<td class="er">SendMail电子邮件支持</td>
<td class="fc"><?=get_extension_funcs('standard') ? YES : NO ?></td>
</tr>
</table>
<table width="100%" class="inp">
<tr>
<td colspan="2" class="ec" width="100%">PHP 缓存组件</td>
</tr>
<tr>
<td class="el" width="50%">Zend OPcache</td>
<td class="el" width="50%">Redis</td>
</tr>
<tr>
<td class="fc"><?=get_extension_funcs('Zend OPcache') ? YES : NO ?></td>
<td class="fc"><?=phpversion('redis') ? YES : NO ?></td>
</tr>
<tr>
<td colspan="6" class="ft"><?=PHP_SAPI .' PHP/'.PHP_VERSION?>不完全包括以上组件，不同PHP版本能适配的外部扩展不同，如显示未开启请查看是否适配当前版本后再加载!</td>
</tr>
</table>
<table width="100%" class="inp">
<tr>
<td colspan="6" class="ec" width="100%">PHP重要参数检测</td>
</tr>
<tr>
<td class="el">Memory限制</td>
<td class="el">Upload限制</td>
<td class="el">POST限制</td>
<td class="el">Execution超时</td>
<td class="el">Input超时</td>
<td class="el">Socket超时</td>
</tr>
<tr>
<td class="fc"><?=ini_get('memory_limit')?></td>
<td class="fc"><?=ini_get('upload_max_filesize')?></td>
<td class="fc"><?=ini_get('post_max_size')?></td>
<td class="fc"><?=ini_get('max_execution_time').'s'?></td>
<td class="fc"><?=ini_get('max_input_time').'s'?></td>
<td class="fc"><?=ini_get('default_socket_timeout').'s'?></td>
</tr>
</table>
<table width="100%" class="inp">
<tr>
<th class="ec">PHP已编译模块检测</th>
</tr>
<tr>
<td class="fl" style="text-align:center;">
<?php
$able=get_loaded_extensions();
foreach ($able as $key=>$value) {
if ($key!=0 && $key%13==0) {
echo '<br />';
}
echo "$value&nbsp;&nbsp;&nbsp;&nbsp;";
}
?>
</td>
</tr>
</table>
<form method="post" action="<?=htmlentities($_SERVER['PHP_SELF'])?>">
<table width="100%" class="inp">
<tr>
<th colspan="4" class="ec">数据库连接测试</th>
</tr>
<tr>
<td colspan="4" class="ft">请及时登录phpMyAdmin修改数据库默认用户名和密码</td>
</tr>
<tr>
<td width="25%" class="er">数据库服务器</td>
<td width="25%" class="fl"><input type="text" name="mysqlHost" value="127.0.0.1" disabled="true"/></td>
<td width="25%" class="er">数据库数据名称</td>
<td width="25%" class="fl"><input type="text" name="mysqlDb" value="test" /></td>
</tr>
<tr>
<td class="er">数据库用户名</td>
<td class="fl"><input type="text" name="mysqlUser" value="root" /></td>
<td class="er">数据库用户密码</td>
<td class="fl"><input type="text" name="mysqlPassword" /></td>
</tr>
<tr>
<td colspan="4" align="center"><input type="submit" value=" 连 接 " name="act" style="height:30px;" /></td>
</tr>
</table>
</form>
<?php if(isset($_POST['act'])) {?>
<table width="100%" class="inp">
<tr>
<th colspan="4" class="ec">数据库测试结果</th>
</tr>
<?php
if ($link) $str1 = '<span style="color: #008000; font-weight: bold;">连接正常</span> ('.mysqli_get_server_info($link).')';
else $str1 = '<span style="color: #ff0000; font-weight: bold;">连接错误</span><br />'.mysqli_connect_error();
?>
<tr>
<td colspan="2" class="er" width="50%">127.0.0.1</td>
<td colspan="2" class="fl" width="50%"><?=$str1?></td>
</tr>
<tr>
<td colspan="2" class="er">数据库<?=$_POST['mysqlDb']?></td>
<td colspan="2" class="fl"><?=(@mysqli_select_db($link, $_POST['mysqlDb']))?'<span style="color: #008000; font-weight: bold;">访问正常</span>':'<span style="color: #ff0000; font-weight: bold;">访问错误</span>'?></td>
</tr>
</table>
<?php }?>
<p style="color:#33384e;font-size:14px;text-align:center; margin-bottom:2px;">
<?php $up_time = sprintf('%0.6f', micro_time_float() - $up_start);?>UPUPW探针版本:<?php echo $version?>&nbsp;&nbsp;&nbsp;页面执行时间 <?php echo $up_time?> 秒&nbsp;&nbsp;&nbsp;消耗内存 <?php echo memory_usage();?>
</p>
<hr style="width:100%; color:#cdcdcd" noshade="noshade" size="1" />
<p style="color:#505050; font-size:14px; text-align:center;">&copy;2015 <a href="http://www.upupw.net">UPUPW绿色服务器平台</a> 版权所有</p>
</div>
</body>
</html>

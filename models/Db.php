<?php
/**
 * Created by PhpStorm.
 * User: liaopeng
 * Date: 2018/12/10
 * Time: 4:31 PM
 */
namespace lpsoft\lpii\models;
class Db extends Build
{
    /**
     * 设置数据库连接
     * @return array
     */
    public static function setDbset($host="127.0.0.1",$port="3306",$dbname="",$username="root",$password="",$charset="utf8"){
        $db_file =  self::getYiiAppPath("common").DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."main-local.php";
        $codes=file_get_contents($db_file);
        $dbarr=require($db_file);
        $dsn='mysql:host='.$host.";";
        if($port&&$port!="3306")
        {
            $dsn.="port=".$port.";";
        }
        $dsn.="dbname=".$dbname;

        $codes=str_replace($dbarr['components']['db']["dsn"],$dsn,$codes);
        $codes=str_replace($dbarr['components']['db']["username"],$username,$codes);
        $codes=str_replace($dbarr['components']['db']["password"],$password,$codes);
        $codes=str_replace($dbarr['components']['db']["charset"],$charset,$codes);
        file_put_contents($db_file,$codes);
    }
    //获取数据库设置
    public static function getDbSet(){
       $db_file =  self::getYiiAppPath("common").DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."main-local.php";
       $redata=[
           'status'=>101,
           "message"=>"common目录下的数据库设置文件读取错误",
           "host"=>"",
           "port"=>"3306",
           "dbname"=>"",
           "username"=>"",
           "password"=>"",
           "charset"=>"utf8",
           
       ];
       if(file_exists($db_file))
       {
           $dbarr=require($db_file);
           if(isset($dbarr['components']['db']))
           {

               $dsn=$dbarr['components']['db']["dsn"];
               $rules="/host=[\w.]+/";

               preg_match_all($rules,$dsn,$re);
               if(isset($re[0][0]))
               {
                   $redata["host"]=str_replace("host=","",$re[0][0]);
               }

               $rules="/port=[\w.]+/";
               preg_match_all($rules,$dsn,$re);
               if(isset($re[0][0]))
               {
                   $redata["port"]=str_replace("port=","",$re[0][0]);
               }
               $rules="/dbname=[\w.]+/";
               preg_match_all($rules,$dsn,$re);
               if(isset($re[0][0]))
               {
                   $redata["dbname"]=str_replace("dbname=","",$re[0][0]);
               }
               $redata["username"]=$dbarr['components']['db']["username"];
               $redata["password"]=$dbarr['components']['db']["password"];
               $redata["charset"]=$dbarr['components']['db']["charset"];
               $redata["status"]=100;
               $redata["message"]="获取数据库设置成功";
           }
       }
       return $redata;
    }

}
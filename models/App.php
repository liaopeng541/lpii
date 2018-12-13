<?php
/**
 * Created by PhpStorm.
 * User: liaopeng
 * Date: 2018/12/10
 * Time: 4:31 PM
 */
namespace lpsoft\lpii\models;
use \yii\base\Model;
class App extends Model
{
    /**
     * 获取所有项目
     */
    public static function getAllApp()
    {
        $a = \Yii::$aliases;
        $sys = ["@web", "@webroot", "@runtime", "@app"];
        $app = [];
        $p=[];
        foreach ($a as $k => $v) {
            if (is_array($v)) {
                unset($a[$k]);
                continue;
            }
            if (strpos($v, 'vendor')) {
                unset($a[$k]);
                continue;
            }

            if (in_array($k, $sys)) {
                unset($a[$k]);
                continue;
            }
            $key = str_replace('@', '', $k);
            $temp=[];
            $temp["name"]=$key;
            $temp['path']=$v;
            $modules=self::getprojModules($v);
            if($modules)
            {
                foreach ($modules as $val)
                {
                    $temp['modules'][]= [
                        'app' => $key,
                        'path' => $v . "/modules/" . $val,
                        'modules' => $val
                    ];
                }
            }



            $p[]=$temp;
        }

        return $p;


    }

    /**
     * 获取项目路径下所有modules
     * @param $projpath
     * @return array
     */
    public static function getprojModules($projpath)
    {
        $projpath .= '/modules';
        $filesnames = [];
        if (is_dir($projpath)) {
            $filesnames = scandir($projpath);
            foreach ($filesnames as $key => $v) {
                if ($v == "." || $v == "..") {
                    unset($filesnames[$key]);
                }
            }
            $filesnames = array_values($filesnames);
        }
        return $filesnames;
    }

    /***
     * 检测项目名是否合法
     */
    public static function checkAppName($appName){
        if(!$appName)
        {
            return "项目名不能为空";
        }
        //是否字母开头
        if(preg_match('/^[a-zA-z_]/u', $appName)<=0) {
            return "项目名首字必须为_或字母";
        }

        //是否含有中文
        if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $appName)>0) {
            return "项目名中不能含有中文";
        }

        //项目是否重复
        $apps=self::getAllApp();
        foreach ($apps as $key=>$val)
        {
            if($val['name']==$appName)
            {
                return "项目名重复,请修改";
            }
        }



        return true;
    }



}
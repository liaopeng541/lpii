<?php
/**
 * Created by PhpStorm.
 * User: liaopeng
 * Date: 2018/12/10
 * Time: 4:31 PM
 */

namespace lpsoft\lpii\models;

use \yii\base\Model;

class Modules extends Model
{
    /***
     * 检测项目名是否合法
     */
    public static function checkModuleName($moduleName,$app){
        if(!$app)
        {
            return "请指定模块所属项目";
        }
        if(!$moduleName)
        {
            return "模块名不能为空";
        }
        //是否字母开头
        if(preg_match('/^[a-zA-z_]/u', $moduleName)<=0) {
            return "模块名首字必须为_或字母";
        }

        //是否含有中文
        if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $moduleName)>0) {
            return "模块名中不能含有中文";
        }

        //模块是否重复
        $apps=App::getAllApp();
        foreach ($apps as $key=>$val)
        {
            if($val['name']==$app)
            {
                if(isset($val['modules']))
                {
                    foreach ($val['modules'] as $v)
                    {
                        if($v['modules']==$moduleName)
                        {
                            return "此模块名己存在此项目中";
                        }
                    }
                }

            }
        }



        return true;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: liaopeng
 * Date: 2018/12/10
 * Time: 4:57 PM
 */

namespace lpsoft\lpii\controllers;


use lpsoft\lpii\libs\common;
use lpsoft\lpii\models\Db;

class DbController extends BuildController
{
    /**
     * 修改数据库连接
     */
    public function actionSetDbSet(){
        $host=P("host") or $this->R(101,"数据库地址不能为空");
        $port=P("port") ? P("port") :"3306";
        $dbname=P("dbname") or $this->R(101,"数据库名称不能为空");
        $username=P("username") or $this->R(101,"数据库用户名不能为空");
        $password=P("password");
        $charset=P("charset") ?P("charset"): "utf8";
        Db::setDbset($host,$port,$dbname,$username,$password,$charset);
        $this->R(100,"设置成功");
    }

    /**
     * 测试数据库连接
     */
    public function actionTestDb(){
        try{
            $db = \Yii::$app->getDb()->getSchema()->getTableNames();

        }catch (\Exception $e)
        {
            $this->R(101,"数据库连接失败，请检查数据库配置");
        }

        $this->R(100);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: liaopeng
 * Date: 2018/12/10
 * Time: 4:57 PM
 */

namespace lpsoft\lpii\controllers;


use lpsoft\lpii\libs\common;
use lpsoft\lpii\models\App;
use lpsoft\lpii\models\Modules;

class ModuleController extends BuildController
{
    public function actionAddmodule(){
        $modules=P("newmoduleName");
        $app=P("appName");
        $checkMsg=Modules::checkModuleName($modules,$app);
        if($checkMsg!==true)
        {
            $this->R(101,$checkMsg);
        }
        $prjpath = \Yii::getAlias('@' . $app);
        //修改配置文件
        $config = file_get_contents($prjpath . "/config/main.php");
        strpos($config, "'modules' => [") or R(100,  "添加失败，此项目不能添加模块");
        $module_class = $app . "\\modules\\" . strtolower($modules) . "\\$modules";
        $generator = $this->loadGenerator("module", ['Generator' => ['moduleClass' => $module_class, "moduleID" => strtolower($modules)]]);
        if ($generator->validate()) {
            $generator->saveStickyAttributes();
            $files = $generator->generate();
            $answers = [];
            foreach ($files as $val) {
                $answers[$val->id] = 1;
            }
            $results = null;
            if ($generator->save($files, $answers, $results)) {
                $str = "'modules' => [
            '" . $generator->moduleID . "'=>[
                    'class' => '" . $generator->moduleClass . "'
            ],\n";
                $config = str_replace("'modules' => [", $str, $config);
                file_put_contents($prjpath . "/config/main.php", $config);
            }

        }
        $this->R(100);
    }
   
}
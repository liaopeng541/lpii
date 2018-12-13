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
use lpsoft\lpii\libs\FileUtil;
class AppController extends BuildController
{
    
    public function actionIndex(){
        die("这里用来展示设置界面");
    }

    
    /**
     * 获取所有项目列表
     */
    public function actionList(){
        
        
        
        $this->R(1);
    }
    
    /**
     * 添加项目
     */
    public function actionAddapp(){
        //进行项目名检测
        $name=P("newAppName");
        $template=P("apptemplate");
        $checkMsg=App::checkAppName($name);
        if($checkMsg!==true)
        {
            $this->R(101,$checkMsg);
        }
        $tplpath=$this->tpl_path.$template;
        $buildpath=$this->top_path.$name;
        //复制项目模板到根目录
        FileUtil::copyDir($tplpath,$buildpath);

        //修改项目配置文件
        $projctMainPath=$buildpath.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."main.php";
        $projctMain=file_get_contents($projctMainPath);
        $config = str_replace($template, $name, $projctMain);
        file_put_contents($projctMainPath, $config);
        $controllerPath=$buildpath.DIRECTORY_SEPARATOR."controllers".DIRECTORY_SEPARATOR."indexController.php";
        $controller=file_get_contents($controllerPath);
        $controller_= str_replace($template, $name, $controller);
        file_put_contents($controllerPath, $controller_);

        //修改common配置
        $commonBootPath=$this->top_path."common".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."bootstrap.php";
        $projctboot=file_get_contents($commonBootPath);
        $projctboot.="\nYii::setAlias('@{$name}', dirname(dirname(__DIR__)) . '/{$name}');";
        file_put_contents($commonBootPath, $projctboot);
        $this->R(100);
        
    }
    
    /**
     * 删除项目
     */
    public function actionDelapp(){
        $name=P("appName");
        $appPath=$this->top_path.$name;
        FileUtil::unlinkDir($appPath);
        //修改common配置
        $commonBootPath=$this->top_path."common".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."bootstrap.php";
        $projctboot=file_get_contents($commonBootPath);

        /**
         * 以后有时间，要把此处修改成正则
         */
        $projctboot=str_replace("\nYii::setAlias('@{$name}', dirname(dirname(__DIR__)) . '/{$name}');","",$projctboot);
        file_put_contents($commonBootPath, $projctboot);
        $this->R(100);

    }

   
    
    

}
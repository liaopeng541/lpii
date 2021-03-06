<?php
/**
 * Created by PhpStorm.
 * User: liaopeng
 * Date: 2018/12/10
 * Time: 3:09 PM
 */
namespace lpsoft\lpii\controllers;
use lpsoft\lpii\models\App;
use lpsoft\lpii\models\Db;
use lpsoft\lpii\models\Modules;
use \yii\web\Controller;
use Yii;
class BuildController extends Controller
{
    public $enableCsrfValidation=false;
    public $layout=false;
    //当前域名
    public $domain;
    //项目根路径
    public $top_path;
    //本扩展路径
    public $lpii_path;
    //模板文件路径
    public $tpl_path;


    //初始货获取路径信息
    public function init()
    {
        $this->domain= Yii::$app->request->getHostInfo();

        $path =  dirname(__FILE__);
        $top_path=explode("vendor",$path);
        $this->lpii_path=str_replace("controllers","",$path);
        $this->tpl_path=$this->lpii_path."tpl".DIRECTORY_SEPARATOR;
        $this->top_path=$top_path[0];

        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * 单个接收
     * @param $key
     * @return null\
     */
    public function P($key)
    {
        $data = isset($_POST[$key])?$_POST[$key]:null;
        return $data;
    }

    /**
     * 统一回复
     * @param int $status
     * @param string $msg
     * @param null $data
     */
    public function R($status=100,$msg="",$data=null){
        \Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        \Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        \Yii::$app->response->data = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
        \Yii::$app->response->send();
        exit();

    }

    /**
     * 初使化所有信息
     */
    public function actionInitdata(){

        //获取数据库设置
        $data['dbset']=Db::getDbSet();
        $data['appList']=App::getAllApp();

        $this->R(100,"",$data);
    }


}
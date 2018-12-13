<?php
/**
 * Created by PhpStorm.
 * User: liaopeng
 * Date: 2018/10/11
 * Time: 下午4:13
 */
function R($status=100,$message="",$data=null)
{

    Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
    Yii::$app->response->data = [
        'status' => $status,
        'msg' => $message,
        'data' => $data
    ];
    Yii::$app->response->send();
    exit();
}
function P($key)
{
    $data = isset($_REQUEST[$key])?$_REQUEST[$key]:null;
    return $data;
}